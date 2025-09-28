let balance = 20.00;
let attempts = 10;

function setNums(nums) {
  document.querySelectorAll('.wheel').forEach((w, i) => {
    w.textContent = nums[i];
  });
}

function play() {
  const n1 = +document.getElementById('n1').value;
  const n2 = +document.getElementById('n2').value;
  const n3 = +document.getElementById('n3').value;
  const mult = Math.max(1, +document.getElementById('mult').value || 1);

  if (attempts <= 0) {
    document.getElementById('msg').textContent = "No attempts left!";
    return;
  }

  const cost = 1 * mult;
  if (balance < cost) {
    document.getElementById('msg').textContent = "Not enough balance!";
    return;
  }

  balance -= cost;
  attempts--;

  const result = [
    Math.floor(Math.random() * 10),
    Math.floor(Math.random() * 10),
    Math.floor(Math.random() * 10)
  ];

  setNums(result);

  let matches = 0;
  if (n1 === result[0]) matches++;
  if (n2 === result[1]) matches++;
  if (n3 === result[2]) matches++;

  let profit = 0;
  let msg = "You lost!";
  if (matches === 1) { profit = 2 * mult; msg = "You matched 1 number!"; }
  if (matches === 2) { profit = 5 * mult; msg = "You matched 2 numbers!"; }
  if (matches === 3) { profit = 10 * mult; msg = "JACKPOT!"; }

  balance += profit;

  document.getElementById('msg').textContent = msg;
  document.getElementById('balance').textContent = balance.toFixed(2);
  document.getElementById('attempts').textContent = attempts;
}
