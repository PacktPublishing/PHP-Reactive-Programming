const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;
const Subject = Rx.Subject;

var data = '[{"name": "John"},{"name": "Bob"},{"name": "Dan"}]';

Observable.of(data)
    .map(response => JSON.parse(response))
    .concatMap(array => Observable.from(array))
    .filter(object => object.name[0].toLowerCase() == "b")
    .subscribe(value => console.log('Next:', value));
