export class Counter {
    #number = 0

    increment() { 
        this.#number++ 
    }

    get value() { 
        return this.#number
    }
}