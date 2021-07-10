// #slide_wrapper
// .slide>a
// #slide_index_btn_wrapper
//  .slide_index_btn data-slide_index

var slide = {
    max_index : 4,
    prev_index : this.max_index, curr_index : 0, next_index : 1,
    target : document.querySelectorAll('.slide'),
    btn : document.querySelectorAll('.slide_index_btn'),
    active : function(index){
        this.target[index].classList.add('active');
        this.btn[index].classList.add('active');
    },
    remove_active : function(index){
        this.target[index].classList.remove('active');
        this.btn[index].classList.remove('active');
    },
    next : function(){
        // 현재 활성화되어있는 slide를 비활성화 시키고 다음 인덱스의 slide를 활성화시키는 메서드
        clearInterval(slide_timer); // 버튼이 눌린 시점에서 시간마다 반복하는 것을 잠시 멈추기
        this.remove_active(this.curr_index); // 이전 인덱스를 비활성화
        this.index_process(this.curr_index +1) // 현재 인덱스를 다음 인덱스로 변경
        this.active(this.curr_index); // 현재 인덱스를 활성화
        slide_timer = setInterval(function(){slide.next();},slide_timer_time); // 시간마다 반복하는 것을 다시 시작
        // var slide_timer ... 으로 하면 객체의 메서드 안에서 새롭게 선언하는 것이기 때문에
        // 다른 곳에서 clearInterval로 반복을 멈출 수 없다.
        // 그래서 var을 붙이지 않고 전역 변수 slide_timer에 넣어주는 것이다.
        // var을 붙이면 안된다.
    },
    prev : function(){
        // 현재 활성화되어있는 slide를 비활성화 시키고 이전 인덱스의 slide를 활성화시키는 메서드
        clearInterval(slide_timer);
        this.remove_active(this.curr_index);
        this.index_process(this.curr_index -1);
        this.active(this.curr_index);
        slide_timer = setInterval(function(){slide.next();},slide_timer_time);
    },
    btn_click : function(self){
        // 클릭한 버튼의 인덱스값을 알아내서 해당 인덱스의 slide를 활성화시켜주는 메서드
        // self.dataset.swiper_btn_index : 버튼이 눌린 인덱스값
        clearInterval(slide_timer);
        this.remove_active(this.curr_index);
        this.index_process(Number(self.dataset.slide_index));
        this.active(this.curr_index);
        slide_timer = setInterval(function(){slide.next();},slide_timer_time);
        console.log("btn_clickk" + self.dataset.slide_index)
    },
    index_process : function(current_index){
        // 인덱스를 입력하면 이전, 현재, 다음 인덱스를 계산해주는 메서드
        // curr_index 처리 과정
        if (current_index > this.max_index){
        current_index = 0;
        }else if (current_index < 0){
        current_index = this.max_index;
        }
        this.curr_index = current_index;
        // prev_index 처리 과정
        this.prev_index = this.curr_index -1;
        if (this.prev_index < 0) this.prev_index = this.max_index;
        // next_index 처리 과정
        this.next_index = this.curr_index +1;
        if (this.next_index > this.max_index) this.next_index = 0;
    }
  }
var slide_timer_time = 2000; // ms단위
var slide_timer = setInterval(function(){slide.next();},slide_timer_time);
slide.btn_click(document.querySelectorAll('.slide_index_btn')[0]);
// 두 순서가 뒤바뀌면 오류가 발생한다.
// slide.btn_click() 안에서 변수 slide_timer를 제어하는 문장이 있는데
// slide_timer가 선언되기 전에 그 문장이 나오면 제대로된 동작이 나올 수 없기 때문이다.