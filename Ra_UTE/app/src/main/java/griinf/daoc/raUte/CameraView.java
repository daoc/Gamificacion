package griinf.daoc.raUte;

import java.io.IOException;

import android.content.Context;
import android.hardware.Camera;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.SurfaceHolder.Callback;

public class CameraView extends SurfaceView implements Callback {
    private Camera camera;

    public CameraView(Context context) {
        super(context);
        getHolder().addCallback(this);
        getHolder().setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);
    }

    @Override
    public void surfaceCreated(SurfaceHolder holder) {
        camera = Camera.open();
    }

    @Override
    public void surfaceChanged(SurfaceHolder holder, int format, int width, int height) {
        Camera.Parameters p = camera.getParameters();
        /**
         List sizes = p.getSupportedPictureSizes();
         Camera.Size result = null;
         for (int i=0;i<sizes.size();i++){
         result = (Camera.Size) sizes.get(i);
         Log.i("PictureSize", "Supported Size. Width: " + result.width + "height : " + result.height);
         }
         */
        p.setPreviewSize(640, 480);
        camera.setParameters(p);

        try {
            camera.setPreviewDisplay(holder);
        } catch (IOException e) {
            e.printStackTrace();
        }
        camera.startPreview();
    }

    @Override
    public void surfaceDestroyed(SurfaceHolder holder) {
        camera.stopPreview();
        camera.release();
        camera = null;
    }
}