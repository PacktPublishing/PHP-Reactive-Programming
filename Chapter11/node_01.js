console.log('Starting application...');

var num = 5;
console.log('num =', num);

setTimeout(() => {
    console.log('Inside setTimeout');
    num += 1;
    console.log('num =', num);
});

console.log('After scheduling another callback');
console.log('num =', num);
