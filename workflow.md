# Workflow สำหรับโปรเจกต์ระบบตรวจสอบสต็อกอุปกรณ์ (Flask on Vercel)

## ภาพรวม
เอกสารนี้อธิบายขั้นตอนการทำงานต่าง ๆ ตั้งแต่การตั้งค่าในเครื่อง ไปจนถึงการdeploy บน Vercel และการบำรุงรักษา

---

## 1. การตั้งค่าในเครื่อง (Local Development)

1. **คลอนหรือดาวน์โหลดโปรเจกต์**
   ```bash
   git clone <repository-url> sts-test
   cd sts-test
   ```

2. **ติดตั้ง dependencies**
   ```bash
   pip install -r requirements.txt
   ```

3. **รันแอปในโหมด development**
   ```bash
   python api/index.py
   ```
   - แอปจะทำงานที่ `http://localhost:5000`
   - ฐานข้อมูล SQLite จะถูกสร้างที่ `/tmp/inventory.db` (ในเครื่องจะเป็นไฟล์ในไดเรกทอรีชั่วคราวของระบบ)

4. **ทดสอบการทำงาน**
   - เข้าสู่ระบบด้วยบัญชีเริ่มต้น: `admin` / `admin123`
   - สำรวจหน้าต่าง ๆ : Dashboard, รายการวัสดุ, เพิ่ม/แก้ไข/ลบ, เพิ่มผู้ใช้ ฯลฯ

---

## 2. การเตรียมพร้อมก่อน Deploy บน Vercel

1. **ตรวจสอบไฟล์สำคัญ**
   - `api/index.py` – จุดเข้า Flask
   - `requirements.txt` – รายการแพ็คเกจ
   - `vercel.json` – การตั้งค่า Vercel (ใช้ `@vercel/python`)
   - โฟลเดอร์ `api/templates/` – ไฟล์ HTML templates

2. **ตั้งค่าตัวแปรสิ่งแวดล้อม (ถ้าต้องการ)**
   - ในกรณีที่ต้องการใช้ฐานข้อมูลภายนอก (เช่น Vercel Postgres, Supabase) ให้เตรียมสตริงเชื่อมต่อ `DATABASE_URL`
   - ตั้งค่า `SECRET_KEY` ที่ซับซ้อนสำหรับการผลิต

3. **ทดสอบการ build ในเครื่อง (ทางเลือก)**
   - Vercel จะติดตั้ง dependencies จาก `requirements.txt` และสร้าง serverless function จาก `api/index.py` โดยอัตโนมัติ
   - คุณสามารถจำลองได้ด้วยการรัน `vercel dev` หากติดตั้ง Vercel CLI แล้ว

---

## 3. การ Deploy บน Vercel

1. **ติดตั้ง Vercel CLI (ถ้ายังไม่มี)**
   ```bash
   npm i -g vercel
   ```

2. **เข้าสู่ระบบ**
   ```bash
   vercel login
   ```

3. **เริ่มการ deploy**
   ```bash
   vercel
   ```
   - ตอบคำถามตามขั้นตอน:
     - Set up and deploy “sts-test”? → `Y`
     - Which scope? → เลือกบัญชีของคุณ
     - Link to existing project? → `N`
     - What’s your project name? → พิมพ์ชื่อที่ต้องการหรือกด Enter เพื่อใช้ `sts-test`
     - In which directory is your code located? → `.`
     - Want to override the settings? → `N` (ใช้การตั้งค่าจาก `vercel.json`)

4. **รอการติดตั้งเสร็จสิ้น**
   - Vercel จะแสดง URL เช่น `https://sts-test.vercel.app`

5. **เข้าใช้งาน**
   - เปิด URL ที่ได้รับ
   - ล็อกอินด้วยบัญชีเริ่มต้น `admin` / `admin123`
   - หากต้องการเปลี่ยนรหัสผ่าน ให้ไปที่หน้า **เพิ่มผู้ใช้** แล้วสร้างบัญชีแอดมินใหม่ จากนั้นลบหรือแก้ไขบัญชี `admin` เดิม

---

## 4. การบำรุงรักษาและอัปเดต

1. **แก้ไขไฟล์ในเครื่อง**
   - แก้ไขไฟล์ใด ๆ ใน `api/` หรือ `api/templates/`

2. **ทดสอบในเครื่องก่อนอัปเดต**
   ```bash
   python api/index.py   # ตรวจสอบว่าไม่มีข้อผิดพลาด
   ```

3. **อัปเดตบน Vercel**
   ```bash
   vercel --prod
   ```
   - หรือหากต้องการสร้าง preview ก่อนผลิต: `vercel`

4. **ตรวจสอบ Logs (หากมีปัญหา)**
   - ไปที่แดชบอร์ด Vercel → โปรเจกต์ของคุณ → Deployments → เลือกการ deploy ล่าสุด → ดูแท็บ **Logs**

5. **จัดการตัวแปรสิ่งแวดล้อม**
   - ไปที่แดชบอร์ด Vercel → Settings → Environment Variables เพื่อเพิ่ม/แก้ไขตัวแปรเช่น `SECRET_KEY`, `DATABASE_URL` เป็นต้น

---

## 5. การสำรองและกู้คืนข้อมูล (สำหรับฐานข้อมูลภายนอก)

- หากคุณใช้ฐานข้อมูลภายนอก (Postgres ฯลฯ) ให้ใช้เครื่องมือสำรองข้อมูลของผู้ให้บริการนั้น ๆ (เช่น `pg_dump` สำหรับ Postgres)
- หากยังใช้ SQLite ที่ `/tmp/inventory.db` (ค่าเริ่มต้น) ข้อมูลจะไม่ถูกสำรองโดยอัตโนมัติและจะหายไปเมื่อฟังก์ชันถูกรีไซเคิล – เหมาะสำหรับการทดสอบเท่านั้น

---

## 6. ลำดับการทำงานทั่วไป (Typical Workflow)

```mermaid
flowchart TD
    A[เริ่มต้นในเครื่อง] --> B[แก้ไขไฟล์หรือเพิ่มฟีเจอร์]
    B --> C[ทดสอบในเครื่อง: python api/index.py]
    C --> D[ตรวจสอบการทำงานผ่านเบราว์เซอร์ที่ localhost:5000]
    D --> E[Commit ไปยัง Git (ถ้ามี repo)]
    E --> F[รัน vercel เพื่อสร้าง preview]
    F --> G[ตรวจสอบผลบน URL preview]
    G --> H[อนุมัติและรัน vercel --prod เพื่ออัปเดต production]
    H --> I[ตรวจสอบว่าเว็บไซต์ทำงานปกติ]
    I --> J[เสร็จสิ้น]
```

---

## หมายเหตุเพิ่มเติม

- ฐานข้อมูลเริ่มต้นใช้ SQLite ใน `/tmp/inventory.db` บน Vercel – ข้อมูลจะหายไปเมื่อฟังก์ชันถูกรีไซเคิล (ประมาณทุก ๆ 1–24 ชั่วโมง) หากต้องการข้อมูลถาวรให้ตั้งค่า `DATABASE_URL` ให้ชี้ไปยังฐานข้อมูลภายนอก
- ระบบจะสร้างบัญชีแอดมินเริ่มต้น (`admin` / `admin123`) เฉพาะเมื่อตาราง `users` ว่างเปล่า
- ทุกครั้งที่แก้ไข `vercel.json` หรือ `requirements.txt` ให้รัน `vercel --prod` อีกครั้งเพื่อให้การเปลี่ยนแปลงมีผล

---

**เสร็จสิ้นเอกสาร workflow**  
เก็บไฟล์นี้เป็น `workflow.md` ในรากโปรเจกต์เพื่ออ้างอิงในอนาคต