// rxjs-test.js
import { of } from 'rxjs';
import { map } from 'rxjs/operators';

// Simple rxjs test
const source = of(1, 2, 3, 4, 5);
const example = source.pipe(
  map(val => val * 10)
);

// Output: 10, 20, 30, 40, 50
example.subscribe(val => console.log(val));
