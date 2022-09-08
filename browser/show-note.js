import $ from './fake-jquery.js';

const notes = {
    'disconnected': {
        class: 'disconnected-status',
        text:  'Disconnected. Try refreshing the page.'
    },
    'waiting': {
        class: 'waiting-status',
        text:  'Waiting for another player to join...'
    },
    'start': {
        class: 'ready-status',
        text:  'The game is on!'
    },
    'busy': {
        class: 'busy-status',
        text:  'Someone else is playing right now...'
    },
    'opponent-disconnected': {
        class: 'opponent-disconnected-status',
        text:  'Your opponent disconnected.'
    },
    'winner': {
        class: 'winner-status',
        text:  'Game over!'
    },
    'your-turn': {
        class: 'your-turn-status',
        text: 'Your turn'
    },
    'opponents-turn': {
        class: 'opponents-turn-status',
        text: "Opponent's turn"
    }
}
  
export default
function showNote(n) {
    $('#note-box').attr('class', notes[n].class);
    $('#note-box').text( notes[n].text );
}

