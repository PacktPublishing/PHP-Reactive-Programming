var $ = null;

// make jQuery work in nodeJS
require("jsdom").env("", function(err, window) {
    $ = require("jquery")(window);
    start();
});

function start() {
    function functionReturningAPromise() {
        var d = $.Deferred();
        setTimeout(() => d.resolve(42), 0);
        return d.promise();
    }

    functionReturningAPromise()
        .then(value => value + 1)
        .then(value => 'result: ' + value)
        .then(value => console.log(value));

}
