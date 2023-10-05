module.exports = {
  friendlyName: "Update",

  description: "Update email template.",

  inputs: {
    name: {
      type: "string",
      required: true,
    },
    content: {
      type: "string",
      required: true,
    },
    subject: {
      type: "string",
      required: true,
    },
    available_tags: {
      type: "string",
    },
    text_version: {
      type: "string",
    },
    id: {
      type: "number",
    },
  },

  exits: {},

  fn: async function (inputs, exits) {
    let objUser = {
      name: inputs.name,
      content: inputs.content,
      subject: inputs.subject,
      available_tags: inputs.available_tags,
      text_version: inputs.text_version,
    };

    var updatedEmail = await Email_template.updateOne({
      id: inputs.id,
    }).set(objUser);

    // console.log(updatedUser);
    return exits.success({
      // token: jwToken.issue({ id: userRecord.id }),
      message: this.res.locals.__("Cms has been updated successfully."),
      data: !_.isEmpty(updatedEmail) ? updatedEmail : {},
      success: true,
    });
  },
};
