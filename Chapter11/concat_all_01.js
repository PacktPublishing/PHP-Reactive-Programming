const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

var data = '[{"name": "John"},{"name": "Bob"},{"name": "Dan"}]';

Observable.of(data)
    .map(data => JSON.parse(data))
    .concatAll()
    .filter(object => object.name[0].toLowerCase() == "b")
    .subscribe(value => console.log('Next:', value));