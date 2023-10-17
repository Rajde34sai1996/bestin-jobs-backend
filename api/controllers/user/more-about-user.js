module.exports = {
  friendlyName: "Create",

  description: "Create more about user.",

  inputs: {
    experience_level: {
      type: "string",
      requied: true,
    },
    experience_month: {
      type: "string",
    },
    experience_year: {
      type: "string",
    },
    wroking_time: {
      type: "string",
      requied: true,
    },
    location: {
      type: "string",
      requied: true,
    },
    travelling_km: {
      type: "string",
      requied: true,
    },
    lat: {
      type: "string",
      requied: true,
    },
    log: {
      type: "string",
      requied: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    try {
      let { id } = this.req.user;
      let where = {
        user_id: id,
        experience_level: inputs.experience_level,
        wroking_time: inputs.wroking_time,
        location: inputs.location,
        travelling_km: inputs.travelling_km,
        lat: inputs.lat,
        log: inputs.log,
      };
      if (where.experience_level == "experienced") {
        if (!inputs.experience_month && !inputs.experience_year) {
          return exits.success({
            success: false,
            message: "Please provide the month and year of your experience",
          });
        }
        where.experience_month = inputs.experience_month;
        where.experience_year = inputs.experience_year;
      }
      let createAbout = await More_about_user.create(where).fetch();
      if (createAbout) {
        return exits.success({
          success: true,
          message: "Record created successfully",
          data: createAbout,
        });
      } else {
        return exits.success({
          success: false,
          message: "Failed to create the record",
        });
      }
    } catch (error) {
      console.log("🚀 ~ file: create.js:64 ~ error:", error);
      // await general.errorLog(error, "more-about-user/create");
      return exits.success({
        success: false,
        message: "Somethinng want wrong!",
      });
    }
  },
};
