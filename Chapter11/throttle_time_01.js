const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

Observable.interval(100)
    .throttleTime(500)
    .subscribe(val => console.log(val));
