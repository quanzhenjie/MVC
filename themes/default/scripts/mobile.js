/****�����¼�ִ��
    * id        ����idֵ
    * timeout   ���������� 500
    * run       ִ�к�������������   ���磺run(a,b,c,d,...)
    */
function LongPressRun(id,timeout,run){
    var timeOutEvent = 0;
    //��ʱ��
    //��ʼ��
    document.getElementById(id).addEventListener("touchstart",function(e){
        timeOutEvent = setTimeout(run+";", timeout);
        //�������ö�ʱ�������峤��N���봥�������¼���ʱ������Լ��ģ����˸о�500����ǳ�����
        return false;
    });
    //�����ָ���ƶ�����ȡ�������¼�����ʱ˵���û�ֻ��Ҫ�ƶ������ǳ���
    document.getElementById(id).addEventListener("touchmove",function(e){
        clearTimeout(timeOutEvent);
        //�����ʱ��
        timeOutEvent = 0;
    });
    //���ͷţ������N�����ھ��ͷţ���ȡ�������¼�����ʱ����ִ��onclickӦ��ִ�е��¼�
    document.getElementById(id).addEventListener("touchend",function(e){
        clearTimeout(timeOutEvent);
        //�����ʱ��
        if (timeOutEvent != 0) {
            //����дҪִ�е����ݣ�����onclick�¼���
        }
        return false;
    });
}
            
            
            
            
            
            