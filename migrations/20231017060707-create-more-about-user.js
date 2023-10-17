'use strict';

module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('more_about_user', {
      id: {
        type: Sequelize.INTEGER,
        primaryKey: true,
        autoIncrement: true,
      },
      user_id: {
        type: Sequelize.INTEGER,
        allowNull: false,
      },
      experience_level: {
        type: Sequelize.ENUM('fresher', 'experienced'),
        allowNull: false,
      },
      experience_month: {
        type: Sequelize.STRING(25),
      },
      experience_year: {
        type: Sequelize.STRING(25),
      },
      working_time: {
        type: Sequelize.STRING(50),
        allowNull: false,
      },
      location: {
        type: Sequelize.STRING,
        allowNull: false,
      },
      travelling_km: {
        type: Sequelize.STRING(50),
        allowNull: false,
      },
      lat: {
        type: Sequelize.STRING,
        allowNull: false,
      },
      log: {
        type: Sequelize.STRING,
        allowNull: false,
      },
      createdAt: {
        type: Sequelize.BIGINT,
        allowNull: false,
      },
      updatedAt: {
        type: Sequelize.BIGINT,
        allowNull: false,
      },
    });
    await queryInterface.addConstraint('more_about_user', {
      fields: ['user_id'],
      type: 'foreign key',
      name: 'fk_request_to_users',
      references: {
        table: 'users',
        field: 'id'
      },
      onDelete: 'CASCADE',
      onUpdate: 'CASCADE'
    });
  },

  down: async (queryInterface, Sequelize) => {
    await queryInterface.removeConstraint(
      "more_about_user",
      "fk_request_to_users"
    );
    await queryInterface.dropTable('more_about_user');
  },
};
