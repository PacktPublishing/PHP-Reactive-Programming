const Rx = require('rxjs/Rx');
const Observable = Rx.Observable;
const BehaviorSubject = Rx.BehaviorSubject;

let A = new BehaviorSubject(1);
let B = new BehaviorSubject(2);
let C = new BehaviorSubject(3);

let AB = Observable.combineLatest(A, B, (a, b) => a + b)
    .do(x => console.log('A + B = ' + x));

let BC = Observable.combineLatest(B, C, (b, c) => b + c)
    .do(x => console.log('B + C = ' + x));

let ABBC = Observable.combineLatest(AB, BC, (ab, bc) => ab + bc)
    .debounceTime(0)
    .do(x => console.log('AB + BC = ' + x));

ABBC.subscribe();

console.log("Updating B = 4 ...");
B.next(4);