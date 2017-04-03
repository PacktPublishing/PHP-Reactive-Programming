const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;

var counter = 1;
var updateTrigger = Observable.defer(() => mockDataFetch())
    .publishReplay(1, 1000)
    .refCount();

function mockDataFetch() {
    return Observable.of(counter++)
        .delay(100);
}

function mockHttpCache() {
    return updateTrigger
        .take(1);
}

mockHttpCache().toPromise()
    .then(val => console.log("Response from 0:", val));

setTimeout(() => mockHttpCache().toPromise()
    .then(val => console.log("Response from 200:", val))
, 200);

setTimeout(() => mockHttpCache().toPromise()
    .then(val => console.log("Response from 1200:", val))
, 1200);

setTimeout(() => mockHttpCache().toPromise()
    .then(val => console.log("Response from 1500:", val))
, 1500);

setTimeout(() => mockHttpCache().toPromise()
    .then(val => console.log("Response from 3500:", val))
, 3500);
