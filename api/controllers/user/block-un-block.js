module.exports = {
  friendlyName: "Block Un Block",

  description: "Block Un Block Admin.",

  inputs: {
    id: {
      type: "number",
      required: true,
    },
    block: {
      type: "boolean",
      required: true,
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    const userDetail = this.req.user;
    if (userDetail.user_type === "admin") {
      const checkUser = await Users.count({ id: inputs.id });
      if (checkUser) {
        var blockUnBlock = await Users.updateOne({ id: inputs.id }).set({
          is_blocked: inputs.block ? 1 : 0,
        });
        if (blockUnBlock) {
          if (inputs.block) {
            await events.sendEvent(blockUnBlock.id, {
              status: "error",
              title: "common.sessionExpiredTitle",
              type: "logout",
              description: "common.accBlocked",
            });
          }
          return exits.success({
            message: this.res.locals.__(
              `Admin has been ${
                inputs.block ? "blocked" : "unblocked"
              } sucessfully.`
            ),
            success: true,
            data: blockUnBlock,
          });
        } else {
          return exits.success({
            message: this.res.locals.__("Invalid User"),
            success: false,
          });
        }
      } else {
        return exits.success({
          message: this.res.locals.__("Invalid User"),
          success: false,
        });
      }
    } else {
      return exits.success({
        message: this.res.locals.__("Invalid User"),
        success: false,
      });
    }
  },
};
