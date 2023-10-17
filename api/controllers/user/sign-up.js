const bcrypt = require("bcryptjs");
const random = require("random-key");
const jwt = require("jsonwebtoken");
module.exports = {
  friendlyName: "Sign up",

  description: "",

  inputs: {
    name: {
      type: "string",
      required: true,
    },
    phone_number: {
      type: "string",
      required: true,
    },
    email: {
      type: "string",
      required: true,
    },
    gender: {
      type: "string",
      required: true,
    },
    dob: {
      type: "string",
      required: true,
    },
    country: {
      type: "string",
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let checkUser = await Users.count({
        email: inputs.email,
      });
      if (checkUser) {
        return exits.success({
          message: this.res.locals.__("User already exists."),
          success: false,
        });
      }
      let where = {
        name: inputs.name,
        phone_number: inputs.phone_number,
        email: inputs.email,
        gender: inputs.gender,
        dob: inputs.dob,
        country: inputs.country,
        role: "user",
      };
      let password = random.generate(10);
      console.log("🚀 ~ file: sign-up.js:61 ~ password:", password)
      let hashPassword = await bcrypt.hash(password, await bcrypt.genSalt(10));
      where.password = hashPassword;
      if (this.req.file) {
        var uploadedFile = this.req.file;
        where.profile_pic = uploadedFile.path;
      }
      let createUser = await Users.create(where).fetch();
      if (createUser) {
        let token = jwt.sign(
          {
            user: createUser,
          },
          sails.config.jwtSecret,
          {
            expiresIn: sails.config.jwtExpires,
          }
        );
        return exits.success({
          success: true,
          data: { user: createUser, token },
        });
      } else {
        return exits.success({
          success: true,
          message: "Something went wrong while creating user!",
        });
      }
    } catch (error) {
      await general.errorLog(error, "user/sign-up");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
