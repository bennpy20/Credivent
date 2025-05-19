const bcrypt = require('bcrypt');

const passwordPlain = '12345'; // password dari user
const saltRounds = 10; // semakin tinggi, semakin aman tapi lebih lambat

bcrypt.hash(passwordPlain, saltRounds, function(err, hash) {
    if (err) throw err;

    console.log('Password asli:', passwordPlain);
    console.log('Hash bcrypt:', hash);

    // Simpan hash ini ke database, bukan password aslinya
});