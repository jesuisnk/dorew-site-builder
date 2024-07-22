// thời điểm tương lai
const TimeInFuture = new Date(UnixTimeInFuture);
// đếm ngược thời gian từ thời điểm hiện tại
function countdown(TimeInFuture, Type) {
    const now = new Date();
    const diff = TimeInFuture - now;
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    switch (Type) {
        case 'd':
            return days;
        case 'h':
            return hours;
        case 'm':
            return minutes;
        case 's':
            return seconds;
        default:
            return hours + ' : ' + minutes + ' : ' + seconds;
    }
}
// in ra kết quả
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('date').textContent = countdown(TimeInFuture, 'd') + ' ngày';
    var timer = setInterval(function () {
        document.querySelector('time').textContent = countdown(TimeInFuture, 'full');
    },1000);
}, false);