from flask import Flask, render_template, request, redirect, url_for, flash, jsonify
from flask_login import LoginManager, UserMixin, login_user, login_required, logout_user, current_user
import sqlite3
import os
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = os.environ.get('SECRET_KEY', 'dev-secret-key-change-in-production')

login_manager = LoginManager()
login_manager.init_app(app)
login_manager.login_view = 'login'

DATABASE = '/tmp/inventory.db'

def get_db():
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    with app.app_context():
        db = get_db()
        cursor = db.cursor()
        # Users table
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                is_admin INTEGER DEFAULT 0
            )
        ''')
        # Inventory summary (same as sys-dashboard)
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS inventory_summary (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                item_name TEXT NOT NULL,
                category TEXT
            )
        ''')
        # Inventory details (SN etc)
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS inventory_details (
                det_id INTEGER PRIMARY KEY AUTOINCREMENT,
                item_id INTEGER NOT NULL,
                serial_number TEXT NOT NULL,
                status TEXT DEFAULT 'Available',
                brand TEXT,
                job_number TEXT,
                dept_used TEXT,
                building TEXT,
                floor TEXT,
                withdrawer_name TEXT,
                receive_date TEXT,
                FOREIGN KEY(item_id) REFERENCES inventory_summary(id)
            )
        ''')
        db.commit()
        # Create default admin if no users exist
        cursor.execute('SELECT COUNT(*) FROM users')
        if cursor.fetchone()[0] == 0:
            hash_pw = generate_password_hash('admin123')
            cursor.execute('INSERT INTO users (username, password_hash, is_admin) VALUES (?, ?, ?)',
                           ('admin', hash_pw, 1))
            db.commit()

@login_manager.user_loader
def load_user(user_id):
    db = get_db()
    user_row = db.execute('SELECT * FROM users WHERE id = ?', (user_id,)).fetchone()
    if user_row:
        user = User()
        user.id = user_row['id']
        user.username = user_row['username']
        user.password_hash = user_row['password_hash']
        user.is_admin = bool(user_row['is_admin'])
        return user
    return None

class User(UserMixin):
    pass

@app.route('/')
@login_required
def index():
    db = get_db()
    # summary stats
    cursor = db.execute('''
        SELECT
            (SELECT COUNT(*) FROM inventory_summary) as total_items,
            (SELECT COUNT(*) FROM inventory_details) as total_sn,
            (SELECT COUNT(*) FROM inventory_details WHERE status = 'Available') as available_sn,
            (SELECT COUNT(*) FROM inventory_details WHERE status = 'Withdrawn') as withdrawn_sn,
            (SELECT COUNT(*) FROM inventory_details WHERE status = 'Repair') as repair_sn
    ''')
    stats = cursor.fetchone()
    # recent items
    items = db.execute('''
        SELECT s.id, s.item_name, s.category,
               COUNT(d.det_id) as total_sn,
               SUM(CASE WHEN d.status = 'Available' THEN 1 ELSE 0 END) as available_sn
        FROM inventory_summary s
        LEFT JOIN inventory_details d ON s.id = d.item_id
        GROUP BY s.id
        ORDER BY s.id DESC
        LIMIT 5
    ''').fetchall()
    return render_template('dashboard.html', stats=stats, items=items)

@app.route('/login', methods=['GET', 'POST'])
def login():
    if current_user.is_authenticated:
        return redirect(url_for('index'))
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        db = get_db()
        user_row = db.execute('SELECT * FROM users WHERE username = ?', (username,)).fetchone()
        if user_row and check_password_hash(user_row['password_hash'], password):
            user = User()
            user.id = user_row['id']
            user.username = user_row['username']
            user.password_hash = user_row['password_hash']
            user.is_admin = bool(user_row['is_admin'])
            login_user(user)
            return redirect(url_for('index'))
        else:
            flash('Invalid username or password', 'error')
    return render_template('login.html')

@app.route('/logout')
@login_required
def logout():
    logout_user()
    return redirect(url_for('login'))

@app.route('/inventory_list')
@login_required
def inventory_list():
    db = get_db()
    items = db.execute('''
        SELECT s.id, s.item_name, s.category,
               COUNT(d.det_id) as total_sn,
               SUM(CASE WHEN d.status = 'Available' THEN 1 ELSE 0 END) as available_sn
        FROM inventory_summary s
        LEFT JOIN inventory_details d ON s.id = d.item_id
        GROUP BY s.id
        ORDER BY s.id
    ''').fetchall()
    return render_template('inventory_list.html', items=items)

@app.route('/add_item', methods=['GET', 'POST'])
@login_required
def add_item():
    if not current_user.is_admin:
        flash('Access denied', 'error')
        return redirect(url_for('index'))
    if request.method == 'POST':
        name = request.form['item_name']
        category = request.form['category']
        db = get_db()
        db.execute('INSERT INTO inventory_summary (item_name, category) VALUES (?, ?)', (name, category))
        db.commit()
        flash('Item added', 'success')
        return redirect(url_for('inventory_list'))
    return render_template('add_item.html')

@app.route('/edit_item/<int:item_id>', methods=['GET', 'POST'])
@login_required
def edit_item(item_id):
    if not current_user.is_admin:
        flash('Access denied', 'error')
        return redirect(url_for('index'))
    db = get_db()
    item = db.execute('SELECT * FROM inventory_summary WHERE id = ?', (item_id,)).fetchone()
    if not item:
        flash('Item not found', 'error')
        return redirect(url_for('inventory_list'))
    if request.method == 'POST':
        name = request.form['item_name']
        category = request.form['category']
        db.execute('UPDATE inventory_summary SET item_name = ?, category = ? WHERE id = ?', (name, category, item_id))
        db.commit()
        flash('Item updated', 'success')
        return redirect(url_for('inventory_list'))
    return render_template('edit_item.html', item=item)

@app.route('/delete_item/<int:item_id>')
@login_required
def delete_item(item_id):
    if not current_user.is_admin:
        flash('Access denied', 'error')
        return redirect(url_for('index'))
    db = get_db()
    db.execute('DELETE FROM inventory_details WHERE item_id = ?', (item_id,))
    db.execute('DELETE FROM inventory_summary WHERE id = ?', (item_id,))
    db.commit()
    flash('Item deleted', 'success')
    return redirect(url_for('inventory_list'))

@app.route('/details/<int:item_id>')
@login_required
def details(item_id):
    db = get_db()
    item = db.execute('SELECT * FROM inventory_summary WHERE id = ?', (item_id,)).fetchone()
    if not item:
        flash('Item not found', 'error')
        return redirect(url_for('inventory_list'))
    sn_list = db.execute('''
        SELECT det_id, serial_number, status, brand, job_number, dept_used, building, floor, withdrawer_name, receive_date
        FROM inventory_details
        WHERE item_id = ?
        ORDER BY det_id
    ''', (item_id,)).fetchall()
    return render_template('details.html', item=item, sn_list=sn_list)

@app.route('/add_sn', methods=['GET', 'POST'])
@login_required
def add_sn():
    if not current_user.is_admin:
        flash('Access denied', 'error')
        return redirect(url_for('index'))
    if request.method == 'POST':
        item_id = request.form['item_id']
        sn = request.form['serial_number']
        status = request.form['status']
        brand = request.form['brand']
        job_number = request.form['job_number']
        dept_used = request.form['dept_used']
        building = request.form['building']
        floor = request.form['floor']
        withdrawer_name = request.form['withdrawer_name']
        receive_date = request.form['receive_date']
        db = get_db()
        db.execute('''
            INSERT INTO inventory_details
            (item_id, serial_number, status, brand, job_number, dept_used, building, floor, withdrawer_name, receive_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ''', (item_id, sn, status, brand, job_number, dept_used, building, floor, withdrawer_name, receive_date))
        db.commit()
        flash('Serial number added', 'success')
        return redirect(url_for('details', item_id=item_id))
    # GET: show form with dropdown of items
    db = get_db()
    items = db.execute('SELECT id, item_name FROM inventory_summary ORDER BY item_name').fetchall()
    return render_template('add_sn.html', items=items)

@app.route('/delete_sn/<int:det_id>')
@login_required
def delete_sn(det_id):
    if not current_user.is_admin:
        flash('Access denied', 'error')
        return redirect(url_for('index'))
    db = get_db()
    sn = db.execute('SELECT item_id FROM inventory_details WHERE det_id = ?', (det_id,)).fetchone()
    if not sn:
        flash('SN not found', 'error')
        return redirect(url_for('index'))
    item_id = sn['item_id']
    db.execute('DELETE FROM inventory_details WHERE det_id = ?', (det_id,))
    db.commit()
    flash('Serial number deleted', 'success')
    return redirect(url_for('details', item_id=item_id))

@app.route('/add_user', methods=['GET', 'POST'])
@login_required
def add_user():
    if not current_user.is_admin:
        flash('Access denied', 'error')
        return redirect(url_for('index'))
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        is_admin = 1 if request.form.get('is_admin') else 0
        db = get_db()
        # check if username exists
        existing = db.execute('SELECT id FROM users WHERE username = ?', (username,)).fetchone()
        if existing:
            flash('Username already exists', 'error')
            return redirect(url_for('add_user'))
        hash_pw = generate_password_hash(password)
        db.execute('INSERT INTO users (username, password_hash, is_admin) VALUES (?, ?, ?)', (username, hash_pw, is_admin))
        db.commit()
        flash('User added', 'success')
        return redirect(url_for('add_user'))
    return render_template('add_user.html')

# API for AJAX fetch SN (optional)
@app.route('/api/fetch_sn/<int:item_id>')
@login_required
def fetch_sn(item_id):
    db = get_db()
    sn_list = db.execute('''
        SELECT det_id, serial_number, status
        FROM inventory_details
        WHERE item_id = ?
        ORDER BY det_id
    ''', (item_id,)).fetchall()
    return jsonify([dict(row) for row in sn_list])

if __name__ == "__main__":
    app.run(debug=True)