/**
 * Users.js
 *
 * @description :: A model definition represents a database table/collection.
 * @docs        :: https://sailsjs.com/docs/concepts/models-and-orm/models
 */
const bcrypt = require("bcryptjs");
module.exports = {
  fetchRecordsOnUpdate: true,
  attributes: {
    name: {
      type: "string",
      allowNull: true,
    },
    email: {
      type: "string",
      required: true,
      isEmail: true,
    },
    password: {
      type: "string",
      protect: true,
      allowNull: true,
    },
    phone_no: {
      type: "string",
      allowNull: true,
    },
    phone_code: {
      type: "string",
    },
    street: {
      type: "string",
      allowNull: true,
    },
    post_code: {
      type: "number",
      allowNull: true,
    },
    city: {
      type: "string",
      allowNull: true,
    },
    country: {
      type: "string",
      allowNull: true,
    },
    country_code: {
      type: "string",
      allowNull: true,
    },
    user_type: {
      type: "string",
      isIn: ["admin", "user"],
      allowNull: true,
    },
    avatar: {
      type: "string",
      allowNull: true,
    },
    is_blocked: {
      type: "number",
      defaultsTo: 0, // 1 = block | 0 = unblock
      isIn: [1, 0],
    },
    token: {
      type: "string",
      allowNull: true,
    },
    is_email_verified: {
      type: "number",
      defaultsTo: 0, // 1 = verified | 0 = not verified
      isIn: [0, 1],
    },
  },
};
