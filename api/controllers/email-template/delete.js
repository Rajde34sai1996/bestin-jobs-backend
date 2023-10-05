module.exports = {
  friendlyName: "Delete",

  description: "Delete email template.",

  inputs: {
    templateId: {
      type: "json",
    },
  },

  exits: {
    success: {
      description: "Template Deleted.",
    },
    redirect: {
      responseType: "redirect",
    },
    invalid: {
      statusCode: 409,
      description: "Invalid requiest",
    },
  },

  fn: async function (inputs, exits) {
    await Email_template.destroy({
      id: inputs.templateId,
    });

    return exits.success({
      success: true,
      message: this.res.locals.__(
        "Email template has been deleted successfully."
      ),
    });
  },
};
