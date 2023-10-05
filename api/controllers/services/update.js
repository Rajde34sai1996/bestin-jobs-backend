module.exports = {
  friendlyName: "Update",

  description: "Update Services.",

  inputs: {
    name: {
      type: "string",
      required: true,
    },
    id: {
      type: "number",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    const userDetail = this.req.user;
    if (userDetail.user_type === "admin") {
      var name = inputs.name.trim();
      var checkService = await Services.count({
        name,
      });
      if (checkService) {
        return exits.success({
          message: this.res.locals.__("Service already exists."),
          success: false,
        });
      }
      var updateService = await Services.updateOne({ id: inputs.id }).set({
        name: inputs.name,
      });
      return exits.success({
        message: this.res.locals.__("Service has been added successfully."),
        success: true,
        data: updateService,
      });
    } else {
      return exits.success({
        message: this.res.locals.__("Invalid User"),
        success: false,
      });
    }
  },
};
