var inputs = ['apple', 'banana', 'orange', 'raspberry'];

var sum = inputs
    .map(fruit => fruit.length)
    .filter(len => len > 5)
    .reduce((a, b) => a + b);

console.log(sum);