module.exports = {
  friendlyName: "Create",

  description: "Create email template.",

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
  },

  exits: {
    invalid: {
      statusCode: 409,
    },
    redirect: {
      responseType: "redirect",
    },
  },

  fn: async function (inputs, exits) {
    var createTemplate = await Email_template.create({
      name: inputs.name,
      content: inputs.content,
      slug: user.slugify(inputs.name),
      subject: inputs.subject,
      available_tags: inputs.available_tags,
      text_version: inputs.text_version,
      status: 1,
    }).fetch();
    if (!createTemplate) {
      return exits.success({
        success: false,
      });
    }
    return exits.success({
      message: this.res.locals.__(
        "Email template has been created successfully."
      ),
      data: createTemplate,
      success: true,
    });
  },
};
