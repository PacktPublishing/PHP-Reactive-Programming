const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

Observable.interval(1000)
    .subscribe(val => console.log('#1 Next:', val));
Observable.interval(600)
    .subscribe(val => console.log('#2 Next:', val));