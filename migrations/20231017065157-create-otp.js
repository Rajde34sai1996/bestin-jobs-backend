'use strict';

module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('otp', {
      id: {
        type: Sequelize.INTEGER,
        primaryKey: true,
        autoIncrement: true,
      },
      verify_by: {
        type: Sequelize.STRING,
        allowNull: false,
      },
      otp: {
        type: Sequelize.INTEGER,
        allowNull: false,
      },
      expire_time: {
        type: Sequelize.INTEGER,
        allowNull: false,
      },
    });
  },

  down: async (queryInterface, Sequelize) => {
    await queryInterface.dropTable('otp');
  },
};
