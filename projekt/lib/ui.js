class UIElement {
    x;
    y;
    width;
    height;

    currentState = "default";
    states = {
	    default: {}
    };

    constructor(x, y, width = 40, height = 40, bgColor = color(255, 255, 255)) {
        this.x = x;
        this.y = y;

        this.width = width;
        this.height = height;

        this.states.default.color = bgColor;
    }

    onClick(x, y) {}
    isWithin(x, y) {
	    return (x >= this.x && y >= this.y) && (x <= this.x + this.width && y <= this.y + this.height);
    }
    
    draw() {
        const currentState = this.states[this.currentState];
        push();
        fill(currentState.color);
        noStroke();
        rect(this.x, this.y, this.x + this.width, this.y + this.height);
        pop();
    }
}

class UI extends UIElement {
    states = {
        default: {
            children: []
        }
    };

    constructor(x, y, width, height, bgColor = color(65, 69, 89)) {
		super(x, y, width, height);

		this.states.default.color = bgColor;
    }

    onClick(x, y) {
		const currentState = this.states[this.currentState];

		for (let i = 0; i < currentState.children.length; i++) {
			if (currentState.children[i].isWithin(x, y)) {
				currentState.children[i].onClick(x, y);
			}
		}

		this.states[this.currentState] = currentState;
    }

    draw() {
		const currentState = this.states[this.currentState];
		super.draw();

		for (let i = 0; i < currentState.children.length; i++) {
			currentState.children[i].draw();
		}

		this.states[this.currentState] = currentState;
    }
}