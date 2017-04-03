const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

Observable.interval(100)
    .concatMap(val => {
        let obs = Observable.of(val);
        return val % 5 == 0 ? obs.delay(250) : obs;
    })
    .debounceTime(200)
    .subscribe(val => console.log(val));
