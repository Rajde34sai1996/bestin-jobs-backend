'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.bulkInsert('users', [
      {
        name: 'admin',
        email: 'best_in_jobs@yopmail.com',
        password: "$2a$10$CPDKr8fFbqfaHSjeqc4LaOIyRWX1S3dnuWcBWSG0kwGBD39C6ceYW",
        dob: "2023-08-19",
        gender: "male",
        phone_number: "000000000",
        country: "India",
        role: "admin",
        createdAt: Date.now(),
        updatedAt: Date.now(),
      },
    ]);
  },

  async down (queryInterface, Sequelize) {
    /**
     * Add reverting commands here.
     *
     * Example:
     * await queryInterface.dropTable('users');
     */
  }
};
