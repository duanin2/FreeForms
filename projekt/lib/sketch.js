"use strict";

let connectors = [];
let connections = [];
let nodes = [];

let selectedNode;
let draggedNode;

let startConnector;
let endConnector;

let width;
let height;

const headerHeight = 20;headerHeight

function removeIdx(array, idx) {
  return array.slice(0, idx - 1).concat(array.slice(idx + 1, array.length));
}

class Option {
	#name;
	
	constructor(name) {
		this.#name = name;
	}

	get Name() {
		return this.#name;
	}
	set Name(newName) {
		this.#name = newName;
	}
}

class Connector {
  #name;
  #type;
  
  #weight = 8;
  
  #ownerIdx;
  idx;
  
  constructor(type, name = "Example") {
    this.#name = name;
    
    if (type !== "input" && type !== "output") {
      throw new Error("type must be one of 'input', 'output'.");
    }
    this.#type = type;
    
    this.idx = connectors.length;
  }

  draw() {
    push();
    stroke(115, 121, 148);
    strokeWeight(this.#weight);
    
    point(this.#x(), this.#y());

    pop();
  }

  get Name() {
    return this.#name;
  }
  set Name(newName) {
    this.#name = newName;
  }

  get Type() {
    return this.#type;
  }

  get Owner() {
    return this.#ownerIdx;
  }
  own(nodeIdx) {
    this.#ownerIdx = nodeIdx;
  }
  disown(nodeIdx) {
    if (this.#ownerIdx === nodeIdx) {
      this.#ownerIdx = null;
    }
  }

  isHovering(posX, posY) {
    return dist(posX, posY, this.#x(), this.#y()) <= this.#weight / 2;
  }
  onHover() {

	}

  #x() {
    if (this.#ownerIdx === undefined) {
      return 0;
    }
    
    const nodeWidth = nodes[this.#ownerIdx].Width;
    
    const nodeX = nodes[this.#ownerIdx].X;
    
    return nodeX + (this.#type === "input" ? 0 : nodeWidth);
  }
  #y() {
    if (this.#ownerIdx === undefined) {
      return 0;
    }
    
    const nodeConnectors = nodes[this.#ownerIdx][this.#type === "input" ? "Inputs" : "Outputs"];
    
    const nodeHeight = nodes[this.#ownerIdx].Height - 20;
    
    const nodeY = nodes[this.#ownerIdx].Y + headerHeight;
    
    return nodeY + nodeHeight / (nodeConnectors.length + 1) * (nodeConnectors.indexOf(this.idx) + 1);
  }

  get Y() {
    return this.#y();
  }

  get X() {
    return this.#x();
  }
}

class Connection {
  #startConnector;
  #endConnector;
  
  idx;
  
  constructor(startConnector, endConnector) {
    if (connectors[startConnector.Type !== "output"]) {
      throw new Error("Starting connector must be of type 'output'.");
    } else if (connectors[endConnector.Type !== "input"]) {
      throw new Error("Ending connector must be of type 'input'.");
    }
    
    for (let i = 0; i < connections.length; i++) {
      if (connections[i].Start === startConnector) {
        connections = removeIdx(connections, i);
      }
    }
    
    this.#startConnector = startConnector;
    this.#endConnector = endConnector;
    
    this.idx = connections.length;
  }

  draw() {
    const startConnector = connectors[this.#startConnector];
    const endConnector = connectors[this.#endConnector];
    
    push();
    beginShape(LINES);
    vertex(startConnector.X, startConnector.Y);
    vertex(endConnector.X, endConnector.Y);
    endShape();
    pop();
  }

  isHovering(posX, posY) {
    const startConnector = connectors[this.#startConnector];
    const endConnector = connectors[this.#endConnector];
    
    const mouseStartDist = dist(posX, posY, startConnector.X, startConnector.Y);
    const mouseEndDist = dist(posX, posY, endConnector.X, endConnector.Y);
    const startEndDist = dist(startConnector.X, startConnector.Y, endConnector.X, endConnector.Y);
    return mouseStartDist + mouseEndDist === startEndDist;
  }
  onHover() {}

  get Start() {
    return this.#startConnector;
  }
  get End() {
    return this.#endConnector;
  }
}

class Node {
  #x;
  #y;
  
  #width = 140;
  #height = 2 * headerHeight;
  
  #ownedConnectors = [];

	#options = [];
	#selectedOption = 0;
  
  #name;
  
  idx;

  #getConnectorsOfType(type) {
    let result = [];
    
    for (let connectorIdx of this.#ownedConnectors) {
      if (connectors[connectorIdx].Type === type) {
        result.push(connectorIdx);
      }
    }
    
    return result;
  }
  
  constructor(initialX, initialY, name = "Example") {
    this.#x = initialX;
    this.#y = initialY;
    
    this.#name = name;
    
    this.idx = nodes.length;
  }

  draw() {
    const nodeX = this.#x;
    const nodeY = this.#y;
    
    const nodeWidth = this.#width;
    const nodeHeight = this.#height;
    
    push();
    fill(81, 87, 109);
    stroke(115, 121, 148);
    
    // Body
    beginShape();
    vertex(nodeX, nodeY + headerHeight);
    vertex(nodeX + nodeWidth, nodeY + headerHeight);
    vertex(nodeX + nodeWidth, nodeY + nodeHeight);
    vertex(nodeX, nodeY + nodeHeight);
    endShape(CLOSE);
    
    // Header
    beginShape();
    vertex(nodeX, nodeY);
    vertex(nodeX + nodeWidth, nodeY);
    vertex(nodeX + nodeWidth, nodeY + headerHeight);
    vertex(nodeX, nodeY + headerHeight);
    endShape(CLOSE);
    pop();
    
    push();
    fill(198, 208, 245);
    noStroke();
    textSize(11);
    textAlign(CENTER, CENTER);
    text(this.#name, nodeX + nodeWidth / 2, nodeY + 10);
    pop();
  }

  isHoveringBody(posX, posY) {
    return (posX >= this.#x && posX <= this.#x + this.#width) && (posY >= this.#y + headerHeight && posY <= this.#y + this.#height);
  }
  isHoveringHeader(posX, posY) {
    return (posX >= this.#x && posX <= this.#x + this.#width) && (posY >= this.#y && posY <= this.#y + headerHeight);
  }

  onHoverBody() {}
  onHoverHeader() {}

  ownConnector(connectorIdx) {
    connectors[connectorIdx].own(this.idx);
    this.#ownedConnectors.push(connectorIdx);
  }
  disownConnector(connectorIdx) {
    let idx = this.#ownedConnectors[connectorIdx].idx;
    connectors[idx].disown(this.idx);
    this.#ownedConnectors = removeIdx(this.#ownedConnectors, connectorIdx);
  }

  get X() {
    return this.#x;
  }
  set X(newX) {
    this.#x = newX;
  }
  get Y() {
    return this.#y;
  }
  set Y(newY) {
    this.#y = newY;
  }

  get Width() {
    return this.#width;
  }
  set Width(newWidth) {
    this.#width = newWidth;
  }
  get Height() {
    return this.#height;
  }
  set Height(newHeight) {
    this.#height = newHeight;
  }

  get Inputs() {
    return this.#getConnectorsOfType("input");
  }
  get Outputs() {
    return this.#getConnectorsOfType("output");
  }

	get Name() {
		return this.#name;
	}
	set Name(newName) {
		this.#name = newName;
	}

	get Options() {
		return this.#options;
	}
	addOption(option) {
    this.#height = headerHeight * max(this.#options.length + 1, 2);
    let idx = connectors.push(new Connector("output", option));
    this.#options.push(new Option(option, idx));
    this.ownConnector(idx);
	}
	removeOption(optionIdx) {
    let connectorIdx = this.#options[optionIdx].connectorIdx;
    let globalConnectorIdx = this.#ownedConnectors[connectorIdx];
		this.#options = removeIdx(this.#options, optionIdx);
    this.#height = headerHeight * max(this.#options.length + 1, 2);
    this.disownConnector(connectorIdx);
    connectors = removeIdx(connectors, globalConnectorIdx);
	}
	get SelectedOption() {
		return this.#selectedOption;
	}
	selectOption(optionIdx) {
		this.#selectedOption = optionIdx;
	}
}

function setup() {
	width = documentWidth() * 0.95 - 40;
	height = documentHeight() - documentHeight() * 0.20 - 70;
  createCanvas(width, height, document.getElementById('p5jsCanvas'));

	// Start node
	nodes.push(new Node(70, height / 2 - 20, "Start"));
	connectors.push(new Connector("output", "Start"));
	nodes[0].Height = 40;
	nodes[0].Width = 40;
	nodes[0].ownConnector(0);

	// End node
	nodes.push(new Node(width - 110, height / 2 - 20, "End"));
	connectors.push(new Connector("input", "End"));
	nodes[1].Height = 40;
	nodes[1].Width = 40;
	nodes[1].ownConnector(1);
}

function draw() {
  background(65, 69, 89);
  for (let node of nodes) {
    node.draw();
    if (node.isHoveringBody(mouseX, mouseY)) {
      node.onHoverBody();
    }
    if (node.isHoveringHeader(mouseX, mouseY)) {
      node.onHoverHeader();
    }
  }
  for (let connection of connections) {
    connection.draw();
    if (connection.isHovering(mouseX, mouseY)) {
      connection.onHover();
    }
  }
  for (let connector of connectors) {
    connector.draw();
    if (connector.isHovering(mouseX, mouseY)) {
      connector.onHover();
    }
  }
  
  if (startConnector != undefined) {
    line(connectors[startConnector].X, connectors[startConnector].Y, mouseX, mouseY);
  } else if (endConnector != undefined) {
    line(connectors[endConnector].X, connectors[endConnector].Y, mouseX, mouseY);
  }
}

function mouseDragged() {
  let isBeingDragged = false;
  for (let i = 0; i < nodes.length; i++) {
    if (draggedNode != undefined) {
      if (nodes[draggedNode].isHoveringHeader(mouseX - movedX, mouseY - movedY) && i == draggedNode) {
        nodes[i].X += movedX;
        nodes[i].Y += movedY;

        isBeingDragged = true;
        
        break;
      }
    } else {
      if (nodes[i].isHoveringHeader(mouseX - movedX, mouseY - movedY)) {
        nodes[i].X += movedX;
        nodes[i].Y += movedY;
        
        isBeingDragged = true;
        draggedNode = i;

        break;
      }
    }
  }
  if (!isBeingDragged) {
    draggedNode = undefined;
  }
}

function mouseClicked() {
	if ((mouseX < 0 || mouseX > width) || (mouseY < 0 || mouseY > height)) {
		return;
	}

  let clickedIn = false;
  
  for (let i = 0; i < connectors.length; i++) {
    if (connectors[i].isHovering(mouseX, mouseY)) {
      clickedIn = true;
      if (connectors[i].Type === "input") {
        endConnector = i;
      } else {
        startConnector = i;
      }
    }
  }
  
  if (!clickedIn) {
    startConnector = undefined;
    endConnector = undefined;
  }
  
  if (startConnector != undefined && endConnector != undefined) {
    connections.push(new Connection(startConnector, endConnector));
    startConnector = undefined;
    endConnector = undefined;
  }
}

addEventListener("resize", (event) => {
	width = floor(documentWidth() * 0.95 - 40);
	height = floor(documentHeight() - (documentHeight() * 0.20) - 40);
	resizeCanvas(width, height);
});

function newNode() {
	let nodeIdx = nodes.push(new Node(width / 2 - 70, height / 2 - 20, "Question")) - 1;
  let connectorIdx = connectors.push(new Connector("input", "Default")) - 1;
	nodes[nodeIdx].ownConnector(connectorIdx);
}

function renameNode() {
	nodes[selectedNode].Name = selected.value;
}

function selectOption() {
	const nodeOptions = nodes[selectedNode].Options;

	/*if (options.value != -1) {
		option.value = nodeOptions[options.value].Name;
	}*/
}

function renameOption() {
	/*if (options.value == -1) {
		nodes[selectedNode].addOption(option.value);
	} else {
		nodes[selectedNode].Options[options.value].Name = option.value;
	}*/

	/*options.innerHTML = "";
	const nodeOptions = nodes[selectedNode].Options;
	for (let i = -1; i < nodeOptions.length; i++) {
		let loopOption = document.createElement("option");
		loopOption.value = i;
		if (i == -1) {
			loopOption.innerHTML = "None";
			loopOption.selected = true;
		} else {
			loopOption.innerHTML = nodeOptions[i].Name;
		}
		options.appendChild(loopOption);
	}*/
}