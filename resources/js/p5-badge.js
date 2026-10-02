import p5 from 'p5';

export function createBadgeSketch() {
    return (sketch) => {
        const words = 'HACEMOS HINCAPAIÉ EN TI';
        let rotation = 0;
        let hovered = false;

        sketch.setup = () => {
            const bounds = sketch._userNode.getBoundingClientRect();
            sketch.createCanvas(bounds.width, bounds.height);
            sketch.pixelDensity(Math.min(window.devicePixelRatio || 1, 1.5));
            sketch.textAlign(sketch.CENTER, sketch.CENTER);
            sketch.textFont('DM Sans');
        };

        sketch.draw = () => {
            sketch.clear();
            const size = Math.min(sketch.width, sketch.height);
            const radius = size * 0.39;
            hovered = sketch.mouseX >= 0 && sketch.mouseX <= size && sketch.mouseY >= 0 && sketch.mouseY <= size;
            rotation += hovered ? 0.0008 : 0.0022;

            sketch.noFill();
            sketch.stroke(255, 250, 245, hovered ? 235 : 170);
            sketch.strokeWeight(0.7);
            sketch.circle(size / 2, size / 2, radius * 2.12);
            sketch.noStroke();
            sketch.fill(255, 250, 245, hovered ? 255 : 215);
            sketch.textSize(size * 0.067);

            sketch.push();
            sketch.translate(size / 2, size / 2);

            for (let index = 0; index < words.length; index += 1) {
                const angle = (index / words.length) * sketch.TWO_PI + rotation - sketch.HALF_PI;
                sketch.push();
                sketch.rotate(angle + sketch.HALF_PI);
                sketch.translate(0, -radius);
                sketch.rotate(-sketch.HALF_PI);
                sketch.text(words[index], 0, 0);
                sketch.pop();
            }

            sketch.pop();
            sketch.noFill();
            sketch.stroke(255, 250, 245, hovered ? 130 : 70);
            sketch.circle(size / 2, size / 2, size * 0.39);
        };

        sketch.windowResized = () => {
            const bounds = sketch._userNode.getBoundingClientRect();
            sketch.resizeCanvas(bounds.width, bounds.height);
        };
    };
}

const badgeContainer = document.querySelector('#badge-canvas');

if (badgeContainer) {
    new p5(createBadgeSketch(), badgeContainer);
}