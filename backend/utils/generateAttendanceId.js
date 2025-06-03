const Attendance = require('../models/Attendance');

async function generateAttendanceId() {
    const lastAttendance = await Attendance.findOne({
        order: [['id', 'DESC']]
    });

    let newIdAttendance = 1;
    if (lastAttendance) {
        const lastIdAttendance = parseInt(lastAttendance.id.split('-')[1]);
        newIdAttendance = lastIdAttendance + 1;
    }

    return `ATD-${newIdAttendance.toString().padStart(3, '0')}`;
}

module.exports = generateAttendanceId;