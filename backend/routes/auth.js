require('dotenv').config();

const express = require('express');
const router = express.Router();
const jwt = require('jsonwebtoken');
const bcrypt = require('bcrypt');
const User = require('../models/User');
const generateUserId = require('../utils/generateUserId');

const JWT_SECRET = process.env.JWT_SECRET;

// Route untuk register
router.post('/register', async (req, res) => {
    const { email, password, name, phone_number, role, acc_status } = req.body;

    try {
        // Cek apakah email sudah ada
        const existingUser = await User.findOne({ where: { email } });
        if (existingUser) {
            return res.status(400).json({ status: 'fail', message: 'Email sudah terdaftar' });
        }

        //// Untuk tabel User
        // Format ID baru jadi "M-XXX"
        const newUserId = await generateUserId();

        // Hash password
        const hashedPassword = await bcrypt.hash(password, 10);

        // Simpan user baru dengan ID yang sudah diformat
        const newUser = await User.create({
            id: newUserId,
            name,
            email,
            password: hashedPassword,
            phone_number,
            role,
            acc_status
        });

        // Buat token JWT
        const token = jwt.sign(
            { id: newUser.id, email: newUser.email },
            JWT_SECRET,
            { expiresIn: '1h' }
        );

        res.json({
            status: 'success',
            token,
            user: {
                id: newUser.id,
                email: newUser.email
            }
        });

    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

// Route untuk login
router.post('/login', async (req, res) => {
    // console.log("Login request body:", req.body);
    const { email, password } = req.body;

    try {
        const user = await User.findOne({ where: { email } });

        if (!user) {
            return res.status(401).json({ status: 'fail', message: 'Email atau password salah' });
        }

        const passwordMatch = await bcrypt.compare(password, user.password);
        if (!passwordMatch) {
            return res.status(401).json({ status: 'fail', message: 'Email atau password salah' });
        }

        const token = jwt.sign(
            { id: user.id, email: user.email, role: user.role },
            JWT_SECRET,
            { expiresIn: '1h' }
        );

        res.json({
            status: 'success',
            token,
            user: {
                id: user.id,
                email: user.email,
                role: user.role
            }
        });

    } catch (error) {
        console.error(error);
        res.status(500).json({ status: 'error', message: 'Server error' });
    }
});

module.exports = router;