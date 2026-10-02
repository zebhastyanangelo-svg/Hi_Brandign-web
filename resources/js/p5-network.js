import p5 from 'p5';

export function createNetworkSketch(container) {
    return (sketch) => {
        let nodes = [];
        let pointer = { x: -1000, y: -1000 };
        let width = 0;
        let height = 0;

        container.addEventListener('pointermove', (event) => {
            const bounds = container.getBoundingClientRect();
            pointer = { x: event.clientX - bounds.left, y: event.clientY - bounds.top };
        }, { passive: true });
        container.addEventListener('pointerleave', () => {
            pointer = { x: -1000, y: -1000 };
        }, { passive: true });

        const seedNodes = () => {
            const count = width < 360 ? 30 : 48;
            nodes = Array.from({ length: count }, () => ({
                x: sketch.random(width * 0.12, width * 0.88),
                y: sketch.random(height * 0.12, height * 0.88),
                dx: sketch.random(-0.22, 0.22),
                dy: sketch.random(-0.22, 0.22),
                size: sketch.random(1.2, 3.3),
            }));
        };

        sketch.setup = () => {
            const bounds = sketch._userNode.getBoundingClientRect();
            width = bounds.width;
            height = bounds.height;
            sketch.createCanvas(width, height);
            sketch.pixelDensity(Math.min(window.devicePixelRatio || 1, 1.5));
            seedNodes();
        };

        sketch.draw = () => {
            sketch.clear();
            const mouseX = pointer.x;
            const mouseY = pointer.y;

            nodes.forEach((node, index) => {
                node.x += node.dx;
                node.y += node.dy;

                if (node.x < 15 || node.x > width - 15) node.dx *= -1;
                if (node.y < 15 || node.y > height - 15) node.dy *= -1;

                const pointerDistance = sketch.dist(node.x, node.y, mouseX, mouseY);
                sketch.noStroke();
                sketch.fill(168, 73, 42, pointerDistance < 95 ? 220 : 145);
                sketch.circle(node.x, node.y, node.size + (pointerDistance < 95 ? 1.8 : 0));

                for (let otherIndex = index + 1; otherIndex < nodes.length; otherIndex += 1) {
                    const other = nodes[otherIndex];
                    const distance = sketch.dist(node.x, node.y, other.x, other.y);
                    const pointerNear = pointerDistance < 110 || sketch.dist(other.x, other.y, mouseX, mouseY) < 110;

                    if (distance < 92 && pointerNear) {
                        sketch.stroke(168, 73, 42, 90 * (1 - distance / 92));
                        sketch.strokeWeight(0.75);
                        sketch.line(node.x, node.y, other.x, other.y);
                    }
                }
            });
        };

        sketch.windowResized = () => {
            const bounds = sketch._userNode.getBoundingClientRect();
            width = bounds.width;
            height = bounds.height;
            sketch.resizeCanvas(width, height);
            seedNodes();
        };
    };
}

const networkContainer = document.querySelector('#network-canvas');

if (networkContainer) {
    new p5(createNetworkSketch(networkContainer), networkContainer);
}