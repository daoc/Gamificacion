package griinf.daoc.raUte;

import android.app.Activity;
import android.graphics.PixelFormat;
import android.opengl.GLSurfaceView;
import android.os.Bundle;
import android.view.MotionEvent;
import android.view.WindowManager.LayoutParams;

import com.threed.jpct.Logger;

public class MainActivity extends Activity {

    private GLSurfaceView mGLView;
    private MyRenderer renderer = null;

    protected float touchTurn = 0;
    protected float touchTurnUp = 0;

    protected float xpos = -1;
    protected float ypos = -1;

    protected void onCreate(Bundle savedInstanceState) {

        Logger.log("onCreate");

        super.onCreate(savedInstanceState);
        mGLView = new GLSurfaceView(this);

        mGLView.setEGLConfigChooser(8, 8, 8, 8, 16, 0);
        mGLView.getHolder().setFormat(PixelFormat.TRANSLUCENT);

        renderer = new MyRenderer(this);
        mGLView.setRenderer(renderer);
        setContentView(mGLView);

        CameraView cameraView = new CameraView(this);
        addContentView(cameraView, new LayoutParams(LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT));
    }

    @Override
    protected void onPause() {
        super.onPause();
        mGLView.onPause();
    }

    @Override
    protected void onResume() {
        super.onResume();
        mGLView.onResume();
    }

    @Override
    public boolean onTouchEvent(MotionEvent me) {

        if (me.getAction() == MotionEvent.ACTION_DOWN) {
            xpos = me.getX();
            ypos = me.getY();
            return true;
        }

        if (me.getAction() == MotionEvent.ACTION_UP) {
            xpos = -1;
            ypos = -1;
            touchTurn = 0;
            touchTurnUp = 0;
            return true;
        }

        if (me.getAction() == MotionEvent.ACTION_MOVE) {
            float xd = me.getX() - xpos;
            float yd = me.getY() - ypos;

            xpos = me.getX();
            ypos = me.getY();

            touchTurn = xd / -100f;
            touchTurnUp = yd / -100f;
            return true;
        }

        try {
            Thread.sleep(15);
        } catch (Exception e) {
            // No need for this...
        }

        return super.onTouchEvent(me);
    }

}
