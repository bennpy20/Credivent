require('dotenv').config();

const express = require('express');
const router = express.Router();

const authRoutes = require('./auth');
const adminRoutes = require('./admin');
const committeeRoutes = require('./committee');

router.use('/auth', authRoutes);
router.use('/admin', adminRoutes);
router.use('/committee', committeeRoutes);

module.exports = router;