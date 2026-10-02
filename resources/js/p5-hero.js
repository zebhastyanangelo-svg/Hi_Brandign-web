import p5 from 'p5';

export function createHeroSketch(container) {
    return (sketch) => {
        const target = { x: 0.5, y: 0.5 };
        let pointer = { x: 0.5, y: 0.5 };
        let width = 0;
        let height = 0;

        container.parentElement?.addEventListener('pointermove', (event) => {
            const bounds = container.getBoundingClientRect();
            target.x = (event.clientX - bounds.left) / Math.max(bounds.width, 1);
            target.y = (event.clientY - bounds.top) / Math.max(bounds.height, 1);
        }, { passive: true });

        sketch.setup = () => {
            const bounds = sketch._userNode.getBoundingClientRect();
            width = bounds.width;
            height = bounds.height;
            sketch.createCanvas(width, height);
            sketch.noFill();
            sketch.pixelDensity(Math.min(window.devicePixelRatio || 1, 1.5));
        };

        sketch.draw = () => {
            sketch.clear();
            const spacing = width < 680 ? 34 : 48;
            const columns = Math.ceil(width / spacing) + 1;
            const rows = Math.ceil(height / spacing) + 1;
            const time = sketch.frameCount * 0.002;
            const mouseX = sketch.lerp(pointer.x, target.x, 0.035);
            const mouseY = sketch.lerp(pointer.y, target.y, 0.035);

            pointer = { x: mouseX, y: mouseY };
            sketch.stroke(238, 195, 166, 44);
            sketch.strokeWeight(0.7);

            for (let row = 0; row < rows; row += 1) {
                sketch.beginShape();

                for (let column = 0; column < columns; column += 1) {
                    const baseX = column * spacing;
                    const baseY = row * spacing;
                    const distance = sketch.dist(baseX / width, baseY / height, pointer.x, pointer.y);
                    const influence = Math.max(0, 1 - distance * 1.35);
                    const wave = sketch.noise(column * 0.075, row * 0.09, time) - 0.5;
                    const displacement = wave * 25 + influence * 18;

                    sketch.curveVertex(baseX, baseY + displacement);
                }

                sketch.endShape();
            }

            sketch.noStroke();
            for (let row = 0; row < rows; row += 1) {
                for (let column = 0; column < columns; column += 1) {
                    const baseX = column * spacing;
                    const baseY = row * spacing;
                    const distance = sketch.dist(baseX / width, baseY / height, pointer.x, pointer.y);

                    if (distance < 0.16) {
                        sketch.fill(236, 190, 159, 55 * (1 - distance / 0.16));
                        sketch.circle(baseX, baseY, 2.4);
                    }
                }
            }
        };

        sketch.windowResized = () => {
            const bounds = container.getBoundingClientRect();
            width = bounds.width;
            height = bounds.height;
            sketch.resizeCanvas(width, height);
        };
    };
}

const heroContainer = document.querySelector('#hero-canvas');

if (heroContainer) {
    new p5(createHeroSketch(heroContainer), heroContainer);
}