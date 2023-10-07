module.exports = {
  errorLog: async function (value, file, parameters) {
    return new Promise(async (resolve, reject) => {
      try {
        if (parameters) {
          parameters =
            typeof parameters === "string"
              ? parameters
              : JSON.stringify(parameters);
        }
        await Error_log.create({
          error_name: value.name ? value.name : "Error",
          error_name: value.name ? value.name : "Error",
          error_message: value.message ? value.message : "",
          error_path: value.stack ? value.stack : null,
          error_folder_path: file ? file : null,
          parameters: "Blank",
        }).fetch();
      } catch (error) {
        resolve();
      }
    });
  },
};
