const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

Observable.of(1)
    .expand(val => {
        if (val > 32) {
            return Observable.empty();
        } else {
            return Observable.of(val * 2);
        }
    })
    .subscribe(val => console.log(val));