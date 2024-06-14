/* recordWeight.js */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.input-group-wrapper');

    document.querySelector('.calculate-button').addEventListener('click', function(event) {
        const height = document.getElementById('height').value;
        const weight = document.getElementById('weight').value;
        const bmiValueElement = document.getElementById('bmiValue');
        const bmiStatusElement = document.getElementById('bmiStatus');
        const bmiMessageElement = document.getElementById('bmiMessage');

        if (height && weight) {
            const bmiValue = (weight / ((height / 100) ** 2)).toFixed(2);
            bmiValueElement.innerText = bmiValue;

            let bmiStatus = '';
            let bmiMessage = '';

            if (bmiValue < 18.5) {
                bmiStatus = '저체중';
                bmiMessage = '체중을 늘리기 위해 노력해보세요.';
            } else if (bmiValue < 24.9) {
                bmiStatus = '정상 체중';
                bmiMessage = '과도한 다이어트보다는 건강한 식습관을 유지해보세요.';
            } else {
                bmiStatus = '과체중';
                bmiMessage = '체중을 줄이기 위해 조금만 더 노력해볼까요?!';
            }

            bmiStatusElement.innerText = bmiStatus;
            bmiMessageElement.innerText = bmiMessage;
        } else {
            alert('신장과 몸무게를 입력해주세요.');
        }
    });
});

function loadChart(event) {
    event.preventDefault();

    const form = event.target;
    const startDate = form.start_date.value;
    const endDate = form.end_date.value;

    if (!startDate || !endDate) {
        alert('시작 날짜와 끝 날짜를 모두 선택하세요.');
        return;
    }

    fetch(`fetchData.php?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            console.log('Data fetched:', data); // 데이터 확인용

            if (!data.weightRecords || data.weightRecords.length === 0) {
                alert('선택한 기간에 몸무게 기록이 없습니다.');
                return;
            }

            const weightRecords = data.weightRecords;

            const dates = weightRecords.map(record => record.record_date);
            const weights = weightRecords.map(record => record.weight);

            const ctx = document.getElementById('weightChart').getContext('2d');
            
            // 이전 차트가 있으면 삭제
            if (window.weightChart && typeof window.weightChart.destroy === 'function') {
                window.weightChart.destroy();
            }
            
            window.weightChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: '몸무게 변화',
                        backgroundColor: 'rgba(0,0,255,1.0)',
                        borderColor: 'rgba(0,0,255,0.1)',
                        data: weights,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'll',
                                displayFormats: {
                                    day: 'MMM D'
                                }
                            },
                            title: {
                                display: true,
                                text: '날짜'
                            }
                        },
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: '몸무게 (kg)'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching weight data:', error));
}
