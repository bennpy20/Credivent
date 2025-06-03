require('dotenv').config();

const express = require('express');
const router = express.Router();

const authRoutes = require('./auth');
const adminRoutes = require('./admin');
const committeeRoutes = require('./committee');
const memberRoutes = require('./member');

router.use('/auth', authRoutes);
router.use('/admin', adminRoutes);
router.use('/committee', committeeRoutes);
router.use('/member', memberRoutes);

module.exports = router;