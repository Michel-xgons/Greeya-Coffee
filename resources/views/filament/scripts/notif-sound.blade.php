<script>
let audio = null;

document.addEventListener('click', () => {
    audio = new Audio('/notif.wav');
    audio.volume = 1;
});

document.addEventListener('livewire:init', () => {
    Livewire.on('play-sound', () => {
        console.log('PLAY SOUND TRIGGERED');

        if (!audio) {
            console.log('Belum klik halaman');
            return;
        }

        audio.currentTime = 0;
        audio.play().then(() => {
            console.log('SUARA DIPUTAR');
        }).catch(e => {
            console.log('ERROR AUDIO:', e);
        });
    });
});
</script>