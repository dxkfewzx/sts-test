<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage SN - คอมพิวเตอร์ตั้งโต๊ะ Dell OptiPlex | SYSTEMS IT</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-blue: #4e73df;
            --sidebar-bg: #ffffff;
            --main-bg: #f4f7fe;
            --text-dark: #2e384d;
            --text-muted: #8492a6;
            --soft-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }

        body {
            font-family: 'Prompt', sans-serif;
            background-color: var(--main-bg);
            color: var(--text-dark);
            margin: 0;
        }

        /* --- Sidebar (Theme Consistency) --- */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            box-shadow: 10px 0 30px rgba(0,0,0,0.03);
            z-index: 1000;
            transition: 0.3s;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-blue);
            display: flex;
            gap: 10px;
        }

        .nav-link-custom {
            padding: 14px 25px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.3s;
            margin: 4px 15px;
            border-radius: 12px;
            font-weight: 500;
        }

        .nav-link-custom i { font-size: 1.2rem; margin-right: 15px; }
        .nav-link-custom:hover { background: #f8f9fc; color: var(--primary-blue); }
        .nav-link-custom.active {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: #fff;
            box-shadow: 0 10px 20px rgba(78, 115, 223, 0.2);
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: 280px;
            padding: 40px;
            transition: 0.3s;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-title h4 { font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
        .header-title p { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0; }

        /* --- Card & Table --- */
        .content-card {
            background: #fff;
            border-radius: 24px;
            border: none;
            box-shadow: var(--soft-shadow);
            padding: 25px;
        }

        .table thead th {
            background: #fcfcfd;
            border-bottom: 1px solid #f1f3f9;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 15px;
        }

        .table tbody td {
            padding: 18px 15px;
            border-bottom: 1px solid #f1f3f9;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        /* --- Status Badges --- */
        .badge-status {
            padding: 6px 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-block;
        }
        .bg-available { background: #e6fffa; color: #00a884; }
        .bg-withdrawn { background: #fff5f5; color: #e53e3e; }
        .bg-repair { background: #fffaf0; color: #dd6b20; }

        /* --- Action Buttons --- */
        .btn-circle {
            width: 38px; height: 38px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            background: #f8f9fc; color: var(--text-muted); border: none; transition: 0.3s;
        }
        .btn-circle:hover { background: var(--primary-blue); color: #fff; transform: translateY(-2px); }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar { left: -100%; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; padding: 20px; padding-top: 80px; }
            .mobile-header { display: flex !important; }
        }

        .mobile-header {
            display: none; background: #fff; padding: 15px 20px; justify-content: space-between;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1001; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="mobile-header">
    <span class="fw-bold text-primary">SYSTEMS IT</span>
    <button class="btn btn-light" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
</div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shield-lock-fill fs-3"></i>
        <span>SYSTEMS IT</span>
    </div>
    <nav>
        <a href="index.php" class="nav-link-custom"><i class="bi bi-grid-fill"></i> แดชบอร์ด</a>
        <a href="inventory_list.php" class="nav-link-custom"><i class="bi bi-laptop"></i> รายการอุปกรณ์</a>
        <a href="report.php" class="nav-link-custom"><i class="bi bi-file-earmark-bar-graph-fill"></i> รายงาน</a>
        <a href="repair_details.php" class="nav-link-custom"><i class="bi bi-wrench-adjustable-circle-fill"></i> ประวัติแจ้งซ่อม</a>
    </nav>
</div>

<div class="main-content">
    <div class="page-header">
        <div class="header-title">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="inventory_list.php" class="text-decoration-none">รายการอุปกรณ์</a></li>
                    <li class="breadcrumb-item active">รายละเอียด Serial Number</li>
                </ol>
            </nav>
            <h4>คอมพิวเตอร์ตั้งโต๊ะ Dell OptiPlex</h4>
            <p>จัดการและตรวจสอบหมายเลขซีเรียลทั้งหมดของอุปกรณ์ชิ้นนี้</p>
        </div>
        <button class="btn btn-success fw-600 rounded-3 shadow-sm px-4 py-2 border-0"
                style="background: #00a884;"
                data-bs-toggle="modal" data-bs-target="#addBulkSNModal">
            <i class="bi bi-plus-lg me-2"></i>เพิ่ม SN จำนวนมาก
        </button>
    </div>

    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
            <span class="fw-bold text-dark"><i class="bi bi-cpu text-primary me-2"></i>รายการทั้งหมดในระบบ</span>
            <span class="badge rounded-pill bg-primary px-3 py-2">รวม 6 เครื่อง</span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle text-center">
                <thead>
                    <tr>
                        <th style="width: 20%;">Serial Number</th>
                        <th style="width: 12%;" class="hide-mobile">ยี่ห้อ</th>
                        <th style="width: 15%;">สถานะ</th>
                        <th style="width: 12%;" class="hide-mobile">เลข Job</th>
                        <th style="width: 15%;" class="hide-mobile">หน่วยงาน</th>
                        <th style="width: 12%;">ผู้เบิก</th>
                        <th style="width: 10%;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample SN data -->
                    <tr>
                        <td class="fw-bold text-dark">SN001234567</td>
                        <td class="text-muted hide-mobile">Dell</td>
                        <td>
                            <span class="badge-status bg-available">
                                Available
                            </span>
                        </td>
                        <td class="fw-600 text-primary hide-mobile">JOB6904001</td>
                        <td class="text-muted small hide-mobile">
                            แผนกเทคโนโลยีสารสนเทศ<br>
                            <small>อาคาร A ชั้น 2</small>
                        </td>
                        <td class="fw-600">John Doe</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-circle btn-edit-sn"
                                    data-id="1"
                                    data-sn="SN001234567"
                                    data-brand="Dell"
                                    data-status="Available"
                                    data-job="JOB6904001"
                                    data-withdrawer="John Doe"
                                    data-dept="แผนกเทคโนโลยีสารสนเทศ"
                                    data-building="อาคาร A"
                                    data-floor="2"
                                    data-bs-toggle="modal" data-bs-target="#editSNModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="process.php?delete_sn=1&item_id=1"
                                   class="btn-circle" onclick="return confirm('ยืนยันการลบ?')">
                                    <i class="bi bi-trash3-fill text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">SN001234568</td>
                        <td class="text-muted hide-mobile">Dell</td>
                        <td>
                            <span class="badge-status bg-withdrawn">
                                Withdrawn
                            </span>
                        </td>
                        <td class="fw-600 text-primary hide-mobile">JOB6904002</td>
                        <td class="text-muted small hide-mobile">
                            แผนกทรัพยากรบุคคล<br>
                            <small>อาคาร B ชั้น 1</small>
                        </td>
                        <td class="fw-600">Jane Smith</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-circle btn-edit-sn"
                                    data-id="2"
                                    data-sn="SN001234568"
                                    data-brand="Dell"
                                    data-status="Withdrawn"
                                    data-job="JOB6904002"
                                    data-withdrawer="Jane Smith"
                                    data-dept="แผนกทรัพยากรบุคคล"
                                    data-building="อาคาร B"
                                    data-floor="1"
                                    data-bs-toggle="modal" data-bs-target="#editSNModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="process.php?delete_sn=2&item_id=1"
                                   class="btn-circle" onclick="return confirm('ยืนยันการลบ?')">
                                    <i class="bi bi-trash3-fill text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">SN001234569</td>
                        <td class="text-muted hide-mobile">Dell</td>
                        <td>
                            <span class="badge-status bg-available">
                                Available
                            </span>
                        </td>
                        <td class="fw-600 text-primary hide-mobile">JOB6904003</td>
                        <td class="text-muted small hide-mobile">
                            แผนกบัญชีและการเงิน<br>
                            <small>อาคาร C ชั้น 3</small>
                        </td>
                        <td class="fw-600">Bob Johnson</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-circle btn-edit-sn"
                                    data-id="3"
                                    data-sn="SN001234569"
                                    data-brand="Dell"
                                    data-status="Available"
                                    data-job="JOB6904003"
                                    data-withdrawer="Bob Johnson"
                                    data-dept="แผนกบัญชีและการเงิน"
                                    data-building="อาคาร C"
                                    data-floor="3"
                                    data-bs-toggle="modal" data-bs-target="#editSNModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="process.php?delete_sn=3&item_id=1"
                                   class="btn-circle" onclick="return confirm('ยืนยันการลบ?')">
                                    <i class="bi bi-trash3-fill text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">SN001234570</td>
                        <td class="text-muted hide-mobile">Dell</td>
                        <td>
                            <span class="badge-status bg-available">
                                Available
                            </span>
                        </td>
                        <td class="fw-600 text-primary hide-mobile">JOB6904004</td>
                        <td class="text-muted small hide-mobile">
                            แผนกการตลาด<br>
                            <small>อาคาร D ชั้น 1</small>
                        </td>
                        <td class="fw-600">Alice Williams</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-circle btn-edit-sn"
                                    data-id="4"
                                    data-sn="SN001234570"
                                    data-brand="Dell"
                                    data-status="Available"
                                    data-job="JOB6904004"
                                    data-withdrawer="Alice Williams"
                                    data-dept="แผนกการตลาด"
                                    data-building="อาคาร D"
                                    data-floor="1"
                                    data-bs-toggle="modal" data-bs-target="#editSNModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="process.php?delete_sn=4&item_id=1"
                                   class="btn-circle" onclick="return confirm('ยืนยันการลบ?')">
                                    <i class="bi bi-trash3-fill text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">SN001234571</td>
                        <td class="text-muted hide-mobile">Dell</td>
                        <td>
                            <span class="badge-status bg-repair">
                                Repair
                            </span>
                        </td>
                        <td class="fw-600 text-primary hide-mobile">JOB6904005</td>
                        <td class="text-muted small hide-mobile">
                            แผนกไอที<br>
                            <small>อาคาร A ชั้น 3</small>
                        </td>
                        <td class="fw-600">David Brown</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-circle btn-edit-sn"
                                    data-id="5"
                                    data-sn="SN001234571"
                                    data-brand="Dell"
                                    data-status="Repair"
                                    data-job="JOB6904005"
                                    data-withdrawer="David Brown"
                                    data-dept="แผนกไอที"
                                    data-building="อาคาร A"
                                    data-floor="3"
                                    data-bs-toggle="modal" data-bs-target="#editSNModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="process.php?delete_sn=5&item_id=1"
                                   class="btn-circle" onclick="return confirm('ยืนยันการลบ?')">
                                    <i class="bi bi-trash3-fill text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-dark">SN001234572</td>
                        <td class="text-muted hide-mobile">Dell</td>
                        <td>
                            <span class="badge-status bg-available">
                                Available
                            </span>
                        </td>
                        <td class="fw-600 text-primary hide-mobile">JOB6904006</td>
                        <td class="text-muted small hide-mobile">
                            แผนกปฏิบัติการ<br>
                            <small>อาคาร B ชั้น 2</small>
                        </td>
                        <td class="fw-600">Sarah Davis</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-circle btn-edit-sn"
                                    data-id="6"
                                    data-sn="SN001234572"
                                    data-brand="Dell"
                                    data-status="Available"
                                    data-job="JOB6904006"
                                    data-withdrawer="Sarah Davis"
                                    data-dept="แผนกปฏิบัติการ"
                                    data-building="อาคาร B"
                                    data-floor="2"
                                    data-bs-toggle="modal" data-bs-target="#editSNModal">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <a href="process.php?delete_sn=6&item_id=1"
                                   class="btn-circle" onclick="return confirm('ยืนยันการลบ?')">
                                    <i class="bi bi-trash3-fill text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Bulk SN Modal -->
<div class="modal fade" id="addBulkSNModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="process.php" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h6 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle-fill me-2 text-success"></i>นำเข้า Serial Number จำนวนมาก</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="action" value="add_sn_bulk">
                <input type="hidden" name="item_id" value="1">
                <div class="mb-3">
                    <label class="form-label fw-bold small">รายการ Serial Number (1 บรรทัดต่อ 1 SN)</label>
                    <textarea name="sn_list" class="form-control" rows="8" style="border-radius: 12px; background: #f8f9fc;" placeholder="ก๊อปปี้ SN มาวางที่นี่..." required></textarea>
                </div>
                <div class="row g-2">
                    <div class="col-6"><label class="small fw-bold">ยี่ห้อ</label><input type="text" name="brand" class="form-control" style="border-radius: 10px;" placeholder="เช่น Dell"></div>
                    <div class="col-6"><label class="small fw-bold">วันที่รับ</label><input type="date" name="receive_date" class="form-control" style="border-radius: 10px;" value="2026-09-28"></div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">ยืนยันนำเข้า</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit SN Modal -->
<div class="modal fade" id="editSNModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="process.php" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h6 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-warning"></i>แก้ไขข้อมูลอุปกรณ์</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="action" value="edit_sn">
                <input type="hidden" name="det_id" id="edit_det_id">
                <input type="hidden" name="item_id" value="1">
                <div class="mb-3"><label class="small fw-bold text-primary">เลข Job / ใบเบิก</label><input type="text" name="job_number" id="edit_job_val" class="form-control" style="border-radius: 10px;"></div>
                <div class="mb-3"><label class="small fw-bold">Serial Number</label><input type="text" name="serial_number" id="edit_sn_val" class="form-control" style="border-radius: 10px;" required></div>
                <div class="row g-2 mb-3">
                    <div class="col-6"><label class="small fw-bold">ยี่ห้อ</label><input type="text" name="brand" id="edit_brand_val" class="form-control" style="border-radius: 10px;"></div>
                    <div class="col-6">
                        <label class="small fw-bold">สถานะ</label>
                        <select name="status" id="edit_status_val" class="form-select" style="border-radius: 10px;">
                            <option value="Available">Available</option>
                            <option value="Withdrawn">Withdrawn</option>
                            <option value="Repair">Repair</option>
                        </select>
                    </div>
                </div>
                <div class="p-3" style="background: #f8f9fc; border-radius: 15px;">
                    <div class="mb-2"><label class="small fw-bold text-muted">ชื่อผู้เบิก</label><input type="text" name="withdrawer_name" id="edit_withdrawer_val" class="form-control form-control-sm"></div>
                    <div class="mb-2"><label class="small fw-bold text-muted">หน่วยงาน</label><input type="text" name="dept_used" id="edit_dept_val" class="form-control form-control-sm"></div>
                    <div class="row g-2">
                        <div class="col-6"><label class="small fw-bold text-muted">อาคาร</label><input type="text" name="building" id="edit_building_val" class="form-control form-control-sm"></div>
                        <div class="col-6"><label class="small fw-bold text-muted">ชั้น</label><input type="text" name="floor" id="edit_floor_val" class="form-control form-control-sm"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4"><button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill shadow-sm">บันทึกการแก้ไข</button></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    document.querySelectorAll('.btn-edit-sn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_det_id').value = this.getAttribute('data-id');
            document.getElementById('edit_sn_val').value = this.getAttribute('data-sn');
            document.getElementById('edit_brand_val').value = this.getAttribute('data-brand') || '';
            document.getElementById('edit_status_val').value = this.getAttribute('data-status') || 'Available';
            document.getElementById('edit_job_val').value = this.getAttribute('data-job') || '';
            document.getElementById('edit_withdrawer_val').value = this.getAttribute('data-withdrawer') || '';
            document.getElementById('edit_dept_val').value = this.getAttribute('data-dept') || '';
            document.getElementById('edit_building_val').value = this.getAttribute('data-building') || '';
            document.getElementById('edit_floor_val').value = this.getAttribute('data-floor') || '';
        });
    });
</script>
</body>
</html>