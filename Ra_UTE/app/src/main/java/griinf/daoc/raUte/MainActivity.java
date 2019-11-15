package griinf.daoc.raUte;

import android.app.Activity;
import android.content.Context;
import android.graphics.PixelFormat;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.opengl.GLSurfaceView;
import android.os.Bundle;
import android.view.MotionEvent;
import android.view.WindowManager.LayoutParams;
import android.widget.Button;

import com.threed.jpct.Logger;

import java.util.Arrays;

public class MainActivity extends Activity {

    private GLSurfaceView mGLView;
    private MyRenderer renderer = null;

    protected float touchTurn = 0;
    protected float touchTurnUp = 0;

    protected float xpos = -1;
    protected float ypos = -1;

    private SensorManager sensorManager;
    private Sensor motion;

    protected final float[] deltaRotationVector = new float[4];

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

        sensorManager = (SensorManager) getSystemService(Context.SENSOR_SERVICE);
        motion = sensorManager.getDefaultSensor(Sensor.TYPE_GYROSCOPE);

        Button b = new Button(this);
        b.setText("Fire!");
        LayoutParams lp = new LayoutParams();
        lp.width = LayoutParams.WRAP_CONTENT;
        lp.height = LayoutParams.WRAP_CONTENT;
//        lp.verticalMargin = LayoutParams.
        addContentView(b, lp);
    }

    SensorEventListener sel = new SensorEventListener() {
        // Create a constant to convert nanoseconds to seconds.
        private static final float NS2S = 1.0f / 1000000000.0f;
        //private final float[] deltaRotationVector = new float[4];
        private float timestamp;

        @Override
        public void onSensorChanged(SensorEvent event) {
            // This timestep's delta rotation to be multiplied by the current rotation
            // after computing it from the gyro sample data.
            if (timestamp != 0) {
                final float dT = (event.timestamp - timestamp) * NS2S;
                // Axis of the rotation sample, not normalized yet.
                float axisX = event.values[0];
                float axisY = event.values[1];
                float axisZ = event.values[2];

                // Calculate the angular speed of the sample
                float omegaMagnitude = (float) Math.sqrt(axisX*axisX + axisY*axisY + axisZ*axisZ);

                // Normalize the rotation vector if it's big enough to get the axis
                // (that is, EPSILON should represent your maximum allowable margin of error)
                if (omegaMagnitude > 0.1) {
                    axisX /= omegaMagnitude;
                    axisY /= omegaMagnitude;
                    axisZ /= omegaMagnitude;
                }

                // Integrate around this axis with the angular speed by the timestep
                // in order to get a delta rotation from this sample over the timestep
                // We will convert this axis-angle representation of the delta rotation
                // into a quaternion before turning it into the rotation matrix.
                float thetaOverTwo = omegaMagnitude * dT / 2.0f;
                float sinThetaOverTwo = (float) Math.sin(thetaOverTwo);
                float cosThetaOverTwo = (float) Math.cos(thetaOverTwo);
                deltaRotationVector[0] = sinThetaOverTwo * axisX;
                deltaRotationVector[1] = sinThetaOverTwo * axisY;
                deltaRotationVector[2] = sinThetaOverTwo * axisZ;
                deltaRotationVector[3] = cosThetaOverTwo;
            }
            timestamp = event.timestamp;
//            float[] deltaRotationMatrix = new float[9];
//            SensorManager.getRotationMatrixFromVector(deltaRotationMatrix, deltaRotationVector);
//            System.out.println(Arrays.toString(deltaRotationVector));
            // User code should concatenate the delta rotation we computed with the current rotation
            // in order to get the updated rotation.
            // rotationCurrent = rotationCurrent * deltaRotationMatrix;

//            tvX.setText(String.valueOf(sensorEvent.values[0]));
//            tvY.setText(String.valueOf(sensorEvent.values[1]));
//            tvZ.setText(String.valueOf(sensorEvent.values[2]));
        }

        @Override
        public void onAccuracyChanged(Sensor sensor, int i) {
        }
    };

    @Override
    protected void onPause() {
        super.onPause();
        sensorManager.unregisterListener(sel);
        mGLView.onPause();
    }

    @Override
    protected void onResume() {
        super.onResume();
        sensorManager.registerListener(sel, motion, SensorManager.SENSOR_DELAY_NORMAL);
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
