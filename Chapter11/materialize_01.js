const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

Observable.range(1, 4)
    .mergeMap(val => {
        let obs = val == 3 ? Observable.throw('error message') : Observable.of(val);
        return obs.materialize();
    })
    // .do(
    //     value => console.log('#1 (do) Next:', value),
    //     error => console.log('#1 (do) Error:', error),
    //     () => console.log('#1 (do) Complete')
    // )
    .filter(notification => notification.kind == 'N')
    .dematerialize()
    .subscribe(
        value => console.log('#2 Next:', value),
        error => console.log('#2 Error:', error),
        () => console.log('#2 Complete')
    );
