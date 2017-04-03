const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

let source1 = Observable.interval(150);
let source2 = Observable.interval(250);

Observable.interval(1000)
    .withLatestFrom(source1, source2)
    .subscribe(response => console.log(response));


