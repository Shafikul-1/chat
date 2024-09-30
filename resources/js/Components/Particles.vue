<script setup>
//import { ref, reactive } from 'vue'
const particlesLoaded = async container => {
    console.log("Particles container loaded", container);
};
</script>

<template>
    <div>

        <vue-particles id="tsparticles" :particlesLoaded="particlesLoaded" :options="{

            fpsLimit: 120,
            interactivity: {
                events: {
                    onClick: {
                        enable: true,
                        mode: 'push'
                    },
                    onHover: {
                        enable: true,
                        // mode: ['connect', 'grab', 'bubble'],
                        mode: ['grab', 'bubble'],
                    },
                },
                modes: {
                    bubble: {
                        distance: 400,
                        duration: 2,
                        opacity: 0.8,
                        size: 10
                    },
                    push: {
                        quantity: 2
                    },
                    add: {
                        quantity: 4,
                        duration: 0.4
                    },
                    connect: { // not work
                        distance: 400,
                        lineLinked: true,
                        opacity: 1,
                        quantity: 8
                    },
                    grab: {
                        distance: 100,
                        links: {
                            opacity: 2
                        }
                    },
                    repulse: {
                        distance: 200,
                        duration: 0.4
                    }
                }
            },
            particles: {
                color: {
                    // value: '#ffffff'
                    value: ['#FDA7DF', '#ff0000', '#00ff00', '#0000ff']
                },
                links: {
                    // color: '#ffffff',
                    color: ['#FDA7DF', '#ffda79', '#00ff00', '#0000ff'],
                    distance: 150,
                    enable: true,
                    opacity: 0.5,
                    width: 1
                },
                move: {
                    direction: 'none',
                    enable: true,
                    random: false,
                    straight: false,
                    outModes: {
                        // default: 'out',
                        // default: 'destroy',
                        default: 'bounce',
                    },
                    speed: { min: 2, max: 5 },
                    // path: {
                    //     enable: true,
                    //     options: {
                    //         waveLength: { min: 3, max: 7 },
                    //         waveHeight: { min: 1, max: 5 }
                    //     },
                    //     generator: 'zigZagPathGenerator'
                    // },
                    // trail: {
                    //     enable: true,
                    //     length: 30,
                    //     fill: {
                    //         color: '#000000'
                    //     }
                    // }
                },
                number: {
                    density: {
                        enable: true,
                    },
                    value: 90
                },
                opacity: {
                    value: 0.5
                },
                shape: {
                    // type: 'square'
                    type: 'square'
                },
                size: {
                    value: { min: 3, max: 5 }
                    // value: 5
                },
            },

            detectRetina: true
        }" />
        <div class="content">
            <slot />
        </div>
    </div>
</template>

<style scoped>
#tsparticles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    z-index: -1;
}

/* পেজ কনটেন্ট */
.content {
    position: relative;
    z-index: 1;
}
</style>
