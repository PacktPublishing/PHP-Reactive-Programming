const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

Rx.Observable.range(1, 8)
    .filter(val => val % 2 == 0)
    .subscribe(val => console.log('Next:', val));
