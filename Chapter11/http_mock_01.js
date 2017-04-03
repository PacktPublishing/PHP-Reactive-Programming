const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;
const Subject = Rx.Subject;

var data = '[{"name": "John"},{"name": "Bob"},{"name": "Dan"}]';

Observable.of(data)
    .map(response => JSON.parse(response))
    .subscribe(value => console.log('Next:', value));
