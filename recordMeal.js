document.addEventListener('DOMContentLoaded', function() {
    // 메뉴 추가 버튼 기능
    document.querySelectorAll('.add-entry').forEach(function(button) {
        button.addEventListener('click', function(event) {
            const card = event.target.parentElement;
            const newEntry = document.createElement('div');
            newEntry.classList.add('entry');
            newEntry.innerHTML = `
                <label>메뉴: </label>
                <input type="text" name="menu[]"><br/>
                <label>칼로리: </label>
                <input type="number" name="calorie[]"><br/>
            `;
            card.insertBefore(newEntry, button);
        });
    });

    // 계산하기 버튼 기능
    document.getElementById('calculate').addEventListener('click', function(event) {
        const dateInput = document.getElementById('date').value;

        if (!dateInput) {
            alert('날짜를 선택해주세요.');
            return;
        }

        let totalCalories = 0;
        const entries = document.querySelectorAll('.entry');

        // 총 칼로리 계산
        entries.forEach(function(entry) {
            const calorieInput = entry.querySelector('input[name="calorie[]"]');
            if (calorieInput.value) {
                totalCalories += parseInt(calorieInput.value);
            }
        });

        // 총 칼로리 hidden input에 설정
        document.getElementById('totalCalories').value = totalCalories;

        // 총 칼로리 표시
        document.getElementById('total-calories').querySelector('h3').innerText = `총 칼로리는 ${totalCalories} kcal 입니다!`;

        // 코멘트 표시
        const comment = document.getElementById('comment');
        const recommendedCalories = 2000; // 하루 권장 칼로리 (대충 설정한 값)
        if (totalCalories > recommendedCalories) {
            comment.innerText = "하루 권장 칼로리를 초과했어요! 칼로리 관리를 더 신경써야겠어요!";
        } else {
            comment.innerText = "하루 권장 칼로리 이내에요! 잘하고 있어요!";
        }
    });
});
