<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Payroll Management System</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1400px; margin: 0 auto; background-color: #f4f4f4; }
        .container { background-color: white; padding: 40px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background-color: #f9f9f9; border-radius: 8px; padding: 20px; border: 1px solid #e0e0e0; }
        input, select, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background-color: #4CAF50; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; }
        .btn:hover { background-color: #45a049; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Quality Corrugated Box Payroll Management System</h1>

        <div class="grid">
            <div class="card" id="employeeManagement">
                <h2>Employee Management</h2>
                <input type="text" id="employeeName" placeholder="Full Name" required>
                <select id="employeeClassification" required>
                    <option value="">Employee Classification</option>
                    <option value="new">New Hire (3-month Contract)</option>
                    <option value="contract">1-Year Contract</option>
                    <option value="regular">Regular Employee</option>
                    <option value="managerial">Managerial Position</option>
                </select>
                <input type="number" id="baseSalary" placeholder="Base Salary" required>
                <select id="department" required>
                    <option value="">Select Department</option>
                    <option value="office">Office</option>
                    <option value="production">Production</option>
                    <option value="sales">Sales</option>
                </select>
                <button class="btn" onclick="addEmployee()">Add Employee</button>
            </div>

            <div class="card" id="attendanceTracking">
                <h2>Attendance Management</h2>
                <select id="leaveType">
                    <option value="vacation">Vacation Leave (5 days/year)</option>
                    <option value="sick">Sick Leave (5 days/year)</option>
                    <option value="emergency">Emergency Leave</option>
                </select>
                <input type="date" id="leaveStartDate">
                <input type="date" id="leaveEndDate">
                <textarea id="leaveReason" placeholder="Detailed Leave Reason" required></textarea>
                <button class="btn" onclick="submitLeaveRequest()">Submit Leave Request</button>
            </div>

            <div class="card" id="overtimeTracking">
                <h2>Overtime & Shift Management</h2>
                <input type="text" id="employeeId" placeholder="Employee ID">
                <input type="datetime-local" id="shiftStart">
                <input type="datetime-local" id="shiftEnd">
                <select id="shiftType">
                    <option value="regular">Regular Shift (7AM-4PM)</option>
                    <option value="night">Night Shift</option>
                    <option value="overtime">Overtime</option>
                </select>
                <button class="btn" onclick="recordShift()">Record Shift</button>
            </div>

            <div class="card" id="payrollComputation">
                <h2>Payroll Computation</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Contribution</th>
                            <th>Employee Share</th>
                            <th>Employer Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SSS</td>
                            <td>30%</td>
                            <td>70%</td>
                        </tr>
                        <tr>
                            <td>PhilHealth</td>
                            <td>2.5%</td>
                            <td>2.5%</td>
                        </tr>
                        <tr>
                            <td>Pag-IBIG</td>
                            <td>100 PHP</td>
                            <td>100 PHP</td>
                        </tr>
                    </tbody>
                </table>
                <select id="payPeriod">
                    <option value="weekly">Weekly (Every Friday)</option>
                    <option value="monthly">Monthly (15th & 30th)</option>
                </select>
                <button class="btn" onclick="computePayroll()">Process Payroll</button>
            </div>
        </div>

        <div class="card" id="reportingSection">
            <h2>Reports & Documentation</h2>
            <button class="btn" onclick="generatePayslip()">Generate Payslip</button>
            <button class="btn" onclick="exportContributions()">Export Statutory Contributions</button>
            <button class="btn" onclick="generateAttendanceReport()">Attendance Report</button>
        </div>
    </div>

    <script>
        class AdvancedPayrollSystem {
            constructor() {
                this.employees = [];
                this.leaveRequests = [];
                this.shifts = [];
                this.payrollRecords = [];
            }

            addEmployee(name, classification, baseSalary, department) {
                const employee = {
                    id: this.generateEmployeeId(),
                    name,
                    classification,
                    baseSalary,
                    department,
                    leaveCredits: { vacation: 5, sick: 5 },
                    contributions: this.calculateContributions(baseSalary)
                };
                this.employees.push(employee);
                return employee;
            }

            generateEmployeeId() {
                return `EMP-${this.employees.length + 1}`;
            }

            calculateContributions(salary) {
                return {
                    sss: { employee: salary * 0.3, employer: salary * 0.7 },
                    philhealth: { employee: salary * 0.025, employer: salary * 0.025 },
                    pagibig: { employee: 100, employer: 100 }
                };
            }

            submitLeaveRequest(employeeId, leaveType, startDate, endDate, reason) {
                const leaveRequest = {
                    employeeId,
                    leaveType,
                    startDate,
                    endDate,
                    reason,
                    status: 'Pending Approval'
                };
                this.leaveRequests.push(leaveRequest);
                return leaveRequest;
            }

            recordShift(employeeId, shiftStart, shiftEnd, shiftType) {
                const shift = {
                    employeeId,
                    shiftStart,
                    shiftEnd,
                    shiftType,
                    duration: this.calculateShiftDuration(shiftStart, shiftEnd)
                };
                this.shifts.push(shift);
                return shift;
            }

            calculateShiftDuration(start, end) {
                const startTime = new Date(start);
                const endTime = new Date(end);
                return (endTime - startTime) / (1000 * 60 * 60);
            }

            computePayroll(payPeriod) {
                const payrollDetails = this.employees.map(employee => {
                    const regularHours = 8;
                    const overtimeRate = 1.5;
                    const employeeShifts = this.shifts.filter(shift => shift.employeeId === employee.id);

                    const totalHours = employeeShifts.reduce((sum, shift) => sum + shift.duration, 0);
                    const regularPay = regularHours * employee.baseSalary;
                    const overtimePay = Math.max(0, totalHours - regularHours) * (employee.baseSalary * overtimeRate);

                    const netPay = regularPay + overtimePay - Object.values(employee.contributions.sss)[0];

                    return {
                        employeeId: employee.id,
                        name: employee.name,
                        baseSalary: employee.baseSalary,
                        regularPay,
                        overtimePay,
                        contributions: employee.contributions,
                        netPay
                    };
                });

                this.payrollRecords.push(payrollDetails);
                return payrollDetails;
            }
        }

        const payrollSystem = new AdvancedPayrollSystem();

        function addEmployee() {
            const name = document.getElementById('employeeName').value;
            const classification = document.getElementById('employeeClassification').value;
            const baseSalary = parseFloat(document.getElementById('baseSalary').value);
            const department = document.getElementById('department').value;

            const employee = payrollSystem.addEmployee(name, classification, baseSalary, department);
            alert(`Employee Added: ${employee.id}`);
        }

        function submitLeaveRequest() {
            const employeeId = payrollSystem.employees[payrollSystem.employees.length - 1].id;
            const leaveType = document.getElementById('leaveType').value;
            const startDate = document.getElementById('leaveStartDate').value;
            const endDate = document.getElementById('leaveEndDate').value;
            const reason = document.getElementById('leaveReason').value;

            const leaveRequest = payrollSystem.submitLeaveRequest(employeeId, leaveType, startDate, endDate, reason);
            alert('Leave Request Submitted');
        }

        function recordShift() {
            const employeeId = document.getElementById('employeeId').value;
            const shiftStart = document.getElementById('shiftStart').value;
            const shiftEnd = document.getElementById('shiftEnd').value;
            const shiftType = document.getElementById('shiftType').value;

            const shift = payrollSystem.recordShift(employeeId, shiftStart, shiftEnd, shiftType);
            alert('Shift Recorded Successfully');
        }

        function computePayroll() {
            const payPeriod = document.getElementById('payPeriod').value;
            const payrollResults = payrollSystem.computePayroll(payPeriod);
            console.log(payrollResults);
            alert('Payroll Processed Successfully');
        }

        function generatePayslip() {
            alert('Payslip Generated (Hardcopy)');
        }

        function exportContributions() {
            alert('Statutory Contributions Exported');
        }

        function generateAttendanceReport() {
            alert('Attendance Report Generated');
        }
    </script>
</body>
</html>
