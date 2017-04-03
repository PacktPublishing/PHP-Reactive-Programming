const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

let source = Observable.create(observer => {
        observer.next(1);
        observer.error('error message');
        observer.next(3);
        observer.complete();
    });

source
    .finally(() => console.log('#1 Finally callback'))
    .subscribe(
        value => console.log('#1 Next:', value),
        error => console.log('#1 Error:', error),
        () => console.log('#1 Complete')
    );

source
    .onErrorResumeNext()
    .finally(() => console.log('#2 Finally callback'))
    .subscribe(
        value => console.log('#2 Next:', value),
        error => console.log('#2 Error:', error),
        () => console.log('#2 Complete')
    );
