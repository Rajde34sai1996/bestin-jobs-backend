const multer = require('multer');
const upload = multer({ dest: 'uploads/' });

module.exports.http = {
  middleware: {
    order: [
      // 'cookieParser',
      // 'session',
      'bodyparser',
      'filemiddleware',
      // 'compress',
      // 'poweredBy',
      'router',
      'www',
      // 'favicon',
    ],

    bodyparser: (function () {
      const bodyparser = require('body-parser');
      return bodyparser.json();
    })(),

    filemiddleware: function (req, res, next) {
      upload.fields([
        { name: 'image1', maxCount: 1 },
        { name: 'image2', maxCount: 1 },
      ])(req, res, function (err) {
        if (err) {
          console.log("err", err);
          return res.serverError(err);
        }
        return next();
      });
    },
  },
};
