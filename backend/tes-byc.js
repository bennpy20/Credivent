const bcrypt = require('bcrypt');
const moment = require('moment-timezone');

const passwordPlain = '12345'; // password dari user
const saltRounds = 10; // semakin tinggi, semakin aman tapi lebih lambat

bcrypt.hash(passwordPlain, saltRounds, function(err, hash) {
    if (err) throw err;

    console.log('Password asli:', passwordPlain);
    console.log('Hash bcrypt:', hash);

    // Simpan hash ini ke database, bukan password aslinya
});


const input = '2025-06-09 10:00';

const formatted = moment.tz(input, 'YYYY-MM-DD HH:mm', 'Asia/Jakarta').format('YYYY-MM-DD HH:mm:ss');

console.log('Input:', input);
console.log('Formatted:', formatted);


const now = new Date();
console.log("Waktu sekarang: " + now)